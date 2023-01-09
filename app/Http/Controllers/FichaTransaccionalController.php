<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mtownsend\XmlToArray\XmlToArray;
use XMLWriter;
use Carbon\Carbon;
include 'class/generarReportes.php';
use generarReportes;

class FichaTransaccionalController extends Controller
{
    function ficha(Request $rq){
        $id = $rq->empresa;
        $anio = $rq->anio;
        $mess = str_pad($rq->mes, 2, "0", STR_PAD_LEFT);
        $mes1 = explode("-", $rq->mes)[0];
        $mes2 = explode("-", $rq->mes)[1];

        if($mes1<10 && ($mes1[0]!="0" && $mes1[0]!=0)){
            $mes1="0".$mes1;
            if($mes2<10 && ($mes2[0]!="0" && $mes2[0]!=0)){
                $mes2="0".$mes2;
            }
        }

        //recupera los valores de facturas y retenciones de facturas de compra ademas de las factura de venta canceladas
        $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = $id");
        $establecimiento = DB::select("SELECT * FROM establecimiento WHERE id_empresa = $id");
        $fv = DB::select("SELECT cl.grupo_tributario, cl.nombre AS nombrecliente, cl.tipo_identificacion, cl.identificacion, sum(fa.subtotal_sin_impuesto) AS base,sum(fa.financiamiento) as financiamiento, sum(fa.iva_12) AS iva, sum(fa.iva_12) AS valoriva, sum(rf.cantidadiva) AS r_iva, sum(rf.cantidadrenta) AS r_renta FROM factura fa INNER JOIN cliente cl ON fa.id_cliente=cl.id_cliente LEFT JOIN retencion_factura rf ON rf.id_factura=fa.id_factura WHERE fa.id_empresa = $id AND fa.estado=1 AND fa.modo=1 AND YEAR(fecha_emision) = $anio AND MONTH(fecha_emision) >= $mes1 AND MONTH(fecha_emision) <= $mes2 GROUP BY fa.id_cliente");
        $nc_venta=DB::select("SELECT cl.grupo_tributario, cl.nombre AS nombrecliente, cl.tipo_identificacion, cl.identificacion, sum(fa.subtotal_sin_impuesto) AS base, sum(fa.iva_12) AS iva, sum(fa.iva_12) AS valoriva
        FROM nota_credito as fa 
        INNER JOIN cliente cl ON fa.id_cliente=cl.id_cliente 
        WHERE fa.id_empresa = $id AND fa.estado=1 AND YEAR(fecha_emision) = $anio AND MONTH(fecha_emision) >= $mes1 AND MONTH(fecha_emision) <= $mes2 GROUP BY fa.id_cliente");
        $fv_cancelados = DB::select("SELECT es.codigo, pe.codigo AS codigope, fa.clave_acceso FROM factura fa INNER JOIN empresa em ON fa.id_empresa=em.id_empresa INNER JOIN establecimiento es ON es.id_empresa=em.id_empresa INNER JOIN punto_emision pe ON pe.id_establecimiento=es.id_establecimiento WHERE fa.id_empresa = $id AND fa.estado=0 AND fa.modo=1 AND YEAR(fecha_emision) = $anio AND MONTH(fecha_emision) >= $mes1 AND MONTH(fecha_emision) <= $mes2");
        $fc = DB::select("SELECT tsu.cod_sustento, fc.id_factcompra, es.codigo AS codigo_establecimiento, pe.codigo AS codigo_pe, pr.*, fc.fech_validez, fc.fech_emision, fc.nro_autorizacion, fc.descripcion, sum(fc.subtotal_sin_impuesto) AS base, sum(fc.subtotal_12) AS iva, sum(fc.iva_12) AS valoriva, ( SELECT COUNT(*) FROM factura_compra_pagos fcp WHERE fcp.id_factura_compra = fc.id_factcompra AND fcp.estado = 1) as formula,tcomp.cod_tipcomprob 
            FROM factura_compra fc 
            INNER JOIN proveedor pr ON fc.id_proveedor=pr.id_proveedor 
            INNER JOIN establecimiento es ON es.id_establecimiento=fc.id_establecimiento 
            INNER JOIN punto_emision pe ON pe.id_punto_emision=fc.id_punto_emision 
            INNER JOIN tipo_sustento tsu ON tsu.id_sustento = fc.id_sustento 
            LEFT  JOIN tipo_comprobante tcomp ON fc.id_tipo_comprobante=tcomp.id_tipcomprobante  WHERE fc.id_empresa = $id AND YEAR(fc.fech_emision) = $anio AND MONTH(fc.fech_emision) >= $mes1 AND MONTH(fc.fech_emision) <= $mes2 AND fc.documento_tributario = 1 AND fc.modo_orden = 0 GROUP BY fc.id_proveedor, es.codigo, pe.codigo, fc.fech_emision, fc.fech_validez, fc.nro_autorizacion, fc.descripcion, fc.id_factcompra");
        $lc = DB::select("SELECT tsu.cod_sustento, fc.id_liquidacion_compra, es.codigo AS codigo_establecimiento, pe.codigo AS codigo_pe, pr.*, fc.fech_validez, fc.fecha_emision, fc.nro_autorizacion, fc.descripcion, sum(fc.subtotal_sin_impuesto) AS base, sum(fc.subtotal_12) AS iva, sum(fc.iva_12) AS valoriva, ( SELECT COUNT(*) FROM liquidacion_compra_pagos fcp WHERE fcp.id_liquidacion_compra = fc.id_liquidacion_compra AND fcp.estado = 1) as formula,tcomp.cod_tipcomprob 
            FROM liquidacion_compra fc 
            INNER JOIN proveedor pr ON fc.id_proveedor=pr.id_proveedor 
            INNER JOIN establecimiento es ON es.id_establecimiento=fc.id_establecimiento 
            INNER JOIN punto_emision pe ON pe.id_punto_emision=fc.id_punto_emision 
            INNER JOIN tipo_sustento tsu ON tsu.id_sustento = fc.id_sustento 
            LEFT  JOIN tipo_comprobante tcomp ON fc.id_tipo_comprobante=tcomp.id_tipcomprobante  WHERE fc.id_empresa = $id AND fc.estado=1 AND YEAR(fc.fecha_emision) = $anio AND MONTH(fc.fecha_emision) >= $mes1 AND MONTH(fc.fecha_emision) <= $mes2 AND fc.documento_tributario = 1 AND fc.estado=1 GROUP BY fc.id_proveedor, es.codigo, pe.codigo, fc.fecha_emision, fc.fech_validez, fc.nro_autorizacion, fc.descripcion, fc.id_liquidacion_compra");
        $nc_compra=DB::select("SELECT '04' as cod_sustento, fc.id_nota_credito_compra, es.codigo AS codigo_establecimiento, pe.codigo AS codigo_pe, pr.*, fc.fecha_expiracion as fech_validez, fc.fecha_emision, fc.clave_acceso as nro_autorizacion, fc.autorizacionfactura as descripcion, sum(fc.subtotal_sin_impuesto) AS base, sum(fc.subtotal_12) AS iva, sum(fc.iva_12) AS valoriva, 0 as formula,'04' cod_tipcomprob 
            FROM nota_credito_compra fc 
            INNER JOIN proveedor pr ON fc.id_proveedor=pr.id_proveedor 
            INNER JOIN establecimiento es ON es.id_establecimiento=fc.id_establecimiento 
            INNER JOIN punto_emision pe ON pe.id_punto_emision=fc.id_punto_emision 
            WHERE fc.id_empresa = $id AND YEAR(fc.fecha_emision) = $anio AND MONTH(fc.fecha_emision) >= $mes1 AND MONTH(fc.fecha_emision) <= $mes2  AND fc.modo = 1 
            GROUP BY fc.id_proveedor, es.codigo, pe.codigo, fc.fecha_emision, fc.fecha_expiracion, fc.clave_acceso, fc.autorizacionfactura, fc.id_nota_credito_compra");
        $retenciones_iva = DB::select("SELECT rfc.id_factura, imp.cod_imp, rfc.cantidadiva, rfc.porcentajeiva FROM retencion_factura_comp rfc INNER JOIN retencion ret ON rfc.id_retencion_iva = ret.id_retencion INNER JOIN impuesto imp ON imp.id_imp = ret.id_impuesto INNER JOIN factura_compra fc ON fc.id_factcompra = rfc.id_factura WHERE fc.id_empresa = $id AND YEAR(fc.fech_emision) = $anio AND MONTH(fc.fech_emision) >= $mes1 AND MONTH(fc.fech_emision) <= $mes2 AND fc.documento_tributario = 1 AND fc.modo_orden = 0 GROUP BY rfc.id_factura, rfc.porcentajeiva, imp.cod_imp, rfc.cantidadiva");
        $retenciones_renta = DB::select("SELECT rfc.id_factura, imp.cod_imp, rfc.cantidadrenta, rfc.porcentajerenta, rfc.baserenta FROM retencion_factura_comp rfc INNER JOIN retencion ret ON rfc.id_retencion_renta = ret.id_retencion INNER JOIN impuesto imp ON imp.id_imp = ret.id_impuesto INNER JOIN factura_compra fc ON fc.id_factcompra = rfc.id_factura WHERE fc.id_empresa = $id AND YEAR(fc.fech_emision) = $anio AND MONTH(fc.fech_emision) >= $mes1 AND MONTH(fc.fech_emision) <= $mes2 AND fc.documento_tributario = 1 AND fc.modo_orden = 0");
        $retenciones_iva_lc = DB::select("SELECT rfc.id_liquidacion_compra, imp.cod_imp, rfc.cantidadiva, rfc.porcentajeiva FROM retencion_liquidacion_compra rfc INNER JOIN retencion ret ON rfc.id_retencion_iva = ret.id_retencion INNER JOIN impuesto imp ON imp.id_imp = ret.id_impuesto INNER JOIN liquidacion_compra fc ON fc.id_liquidacion_compra = rfc.id_liquidacion_compra WHERE fc.id_empresa = $id AND YEAR(fc.fecha_emision) = $anio AND MONTH(fc.fecha_emision) >= $mes1 AND MONTH(fc.fecha_emision) <= $mes2 AND fc.documento_tributario = 1  GROUP BY rfc.id_liquidacion_compra, rfc.porcentajeiva, imp.cod_imp, rfc.cantidadiva");
        $retenciones_renta_lc = DB::select("SELECT rfc.id_liquidacion_compra, imp.cod_imp, rfc.cantidadrenta, rfc.porcentajerenta, rfc.baserenta FROM retencion_liquidacion_compra rfc INNER JOIN retencion ret ON rfc.id_retencion_renta = ret.id_retencion INNER JOIN impuesto imp ON imp.id_imp = ret.id_impuesto INNER JOIN liquidacion_compra fc ON fc.id_liquidacion_compra = rfc.id_liquidacion_compra WHERE fc.id_empresa = $id AND YEAR(fc.fecha_emision) = $anio AND MONTH(fc.fecha_emision) >= $mes1 AND MONTH(fc.fecha_emision) <= $mes2 AND fc.documento_tributario = 1 ");
        file_put_contents(constant("DATA_EMPRESA") . $id . "/comprobantes/factura/anexo_transaccional_".$id."_".$anio."-".$mess.".xml", "");
        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $id . "/comprobantes/factura/anexo_transaccional_".$id."_".$anio."-".$mess.".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
            $xml->startElement('iva');
            //cabezera
                $xml->startElement("TipoIDInformante");
                    $xml->text('R');
                $xml->endElement();
                $xml->startElement("IdInformante");
                    $xml->text($empresa[0]->ruc_empresa);
                $xml->endElement();
                $xml->startElement("razonSocial");
                    $xml->text(str_replace(array('.', ',', '-', '_','ñ','Ñ','á','é','í','ó','ú','Á','É','Í','Ó','Ú'), array(' ',' ',' ',' ','n','N','a','e','i','o','u','A','E','I','O','U'),$empresa[0]->razon_social));
                $xml->endElement();
                $xml->startElement("Anio");
                    $xml->text($anio);
                $xml->endElement();
                $xml->startElement("Mes");
                    if($mes1 == $mes2){
                        $xml->text($mes1);
                    }
                    else{
                        if($mes2<7){
                            $xml->text("06");
                        }
                        else{
                            $xml->text("12");
                        }
                    }
                $xml->endElement();
                if($mes1 != $mes2){    
                    $xml->startElement("regimenMicroempresa");
                        $xml->text("SI");
                    $xml->endElement();
                }
                $xml->startElement("numEstabRuc");
                    $xml->text(str_pad(count($establecimiento), 3, "0", STR_PAD_LEFT));
                $xml->endElement();
                $xml->startElement("totalVentas");
                    $xml->text('0.00');
                $xml->endElement();
                $xml->startElement("codigoOperativo");
                    $xml->text('IVA');
                $xml->endElement();
            if(count($fc)>=1 || count($lc)>=1 || count($nc_compra)>=1){
                $xml->startElement("compras");
            }
            
            if(count($fc)>=1){
                    for($i=0; $i<count($fc); $i++){
                        $xml->startElement("detalleCompras");
                            $xml->startElement("codSustento");
                                $xml->text(str_pad($fc[$i]->cod_sustento, 2, "0", STR_PAD_LEFT));
                            $xml->endElement();
                            if ($fc[$i]->tipo_identificacion == "Cedula") {
                                $xml->startElement("tpIdProv");
                                $xml->text('02');
                                $xml->endElement();
                            } else if ($fc[$i]->tipo_identificacion == "Ruc") {
                                $xml->startElement("tpIdProv");
                                $xml->text('01');
                                $xml->endElement();
                            } else{
                                $xml->startElement("tpIdProv");
                                $xml->text('03');
                                $xml->endElement();
                            }
                            $xml->startElement("idProv");
                                $xml->text(str_replace(array('.', ',', '-', '_', ' '), '', $fc[$i]->identif_proveedor));
                            $xml->endElement();
                            $xml->startElement("tipoComprobante");
                                $xml->text($fc[$i]->cod_tipcomprob);
                            $xml->endElement();
                            $xml->startElement("tipoProv");
                                if($fc[$i]->tipo_contribuyente == "Persona Natural"){
                                    $xml->text('01');
                                }else{
                                    $xml->text('02');
                                }
                            $xml->endElement();
                            $xml->startElement("denoProv");
                                $xml->text(str_replace(array('.', ',', '-', '_','ñ','Ñ','á','é','í','ó','ú','Á','É','Í','Ó','Ú','&','&amp;'), array(' ',' ',' ',' ','n','N','a','e','i','o','u','A','E','I','O','U','',''), $fc[$i]->nombre_proveedor));
                            $xml->endElement();
                            $xml->startElement("parteRel");
                                $xml->text('NO');
                            $xml->endElement();
                            $xml->startElement("fechaRegistro");
                                $xml->text(Carbon::parse($fc[$i]->fech_emision)->format('d/m/Y'));
                            $xml->endElement();
                            $xml->startElement("establecimiento");
                                $xml->text(substr(str_replace(array(" ","-"),array("",""),$fc[$i]->descripcion),0,3));
                            $xml->endElement();
                            $xml->startElement("puntoEmision");
                                $xml->text(substr(str_replace(array(" ","-"),array("",""),$fc[$i]->descripcion),3,3));
                            $xml->endElement();
                            $xml->startElement("secuencial");
                                $xml->text(substr(str_replace(array(" ","-"),array("",""),$fc[$i]->descripcion),6,9));
                            $xml->endElement();
                            $xml->startElement("fechaEmision");
                                $xml->text(Carbon::parse($fc[$i]->fech_emision)->format('d/m/Y'));
                            $xml->endElement();
                            $xml->startElement("autorizacion");
                                $xml->text(str_replace(array("-"," ",".","_"),"",$fc[$i]->nro_autorizacion));
                            $xml->endElement();
                            $xml->startElement("baseNoGraIva");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("baseImponible");
                                $xml->text(number_format($fc[$i]->base - $fc[$i]->iva, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("baseImpGrav");
                                $xml->text(number_format($fc[$i]->iva, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("baseImpExe");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("montoIce");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("montoIva");
                                $xml->text(number_format($fc[$i]->valoriva, 2, '.', ''));
                            $xml->endElement();

                            //si los valores tiene su cantidad guarda en su propio valor
                            $valor10=0;
                            $valor20=0;
                            $valor30=0;
                            $valor50=0;
                            $valor70=0;
                            $valor100=0;
                            for($f=0; $f<count($retenciones_iva); $f++){
                                if($retenciones_iva[$f]->id_factura == $fc[$i]->id_factcompra){
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva[$f]->porcentajeiva) == "10"){
                                        $valor10 = $retenciones_iva[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva[$f]->porcentajeiva) == "20"){
                                        $valor20 = $retenciones_iva[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva[$f]->porcentajeiva) == "30"){
                                        $valor30 = $retenciones_iva[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva[$f]->porcentajeiva) == "50"){
                                        $valor50 = $retenciones_iva[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva[$f]->porcentajeiva) == "70"){
                                        $valor70 = $retenciones_iva[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva[$f]->porcentajeiva) == "100"){
                                        $valor100 = $retenciones_iva[$f]->cantidadiva;
                                    }
                                }
                            }

                            $xml->startElement("valRetBien10");
                                $xml->text(number_format($valor10, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valRetServ20");
                                $xml->text(number_format($valor20, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valorRetBienes");
                                $xml->text(number_format($valor30, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valRetServ50");
                                $xml->text(number_format($valor50, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valorRetServicios");
                                $xml->text(number_format($valor70, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valRetServ100");
                                $xml->text(number_format($valor100, 2, '.', ''));
                            $xml->endElement();

                            $xml->startElement("totbasesImpReemb");
                                $xml->text('0.00');
                            $xml->endElement();

                            $xml->startElement("pagoExterior");
                                $xml->startElement("pagoLocExt");
                                    $xml->text('01');
                                $xml->endElement();
                                $xml->startElement("paisEfecPago");
                                    $xml->text('NA');
                                $xml->endElement();
                                $xml->startElement("aplicConvDobTrib");
                                    $xml->text('NA');
                                $xml->endElement();
                                $xml->startElement("pagExtSujRetNorLeg");
                                    $xml->text('NA');
                                $xml->endElement();
                                $xml->startElement("pagoRegFis");
                                    $xml->text('NA');
                                $xml->endElement();
                            $xml->endElement();

                            $xml->startElement("formasDePago");
                                $xml->startElement("formaPago");
                                    if($fc[$i]->valoriva == 0){
                                        $xml->text('01');
                                    }else{
                                        $xml->text(20);
                                    }
                                $xml->endElement();
                            $xml->endElement();
                            $existerenta = 0 ;
                            for($g=0; $g<count($retenciones_renta); $g++){
                                if($retenciones_renta[$g]->id_factura == $fc[$i]->id_factcompra){
                                    $existerenta = 1;
                                }
                            }
                            if($existerenta == 1){
                                $xml->startElement("air");
                                    /*for($f=0; $f<count($retenciones_iva); $f++){
                                        if($retenciones_iva[$f]->id_factura == $fc[$i]->id_factcompra){
                                            $porcentaje_iva = str_replace(array('%', ' '), '' , $retenciones_iva[$f]->porcentajeiva);
                                            $baseiva =  ($retenciones_iva[$f]->cantidadiva * 100) / $porcentaje_iva;
                                            $xml->startElement("detalleAir");
                                                $xml->startElement("codRetAir");
                                                    $xml->text($retenciones_iva[$f]->cod_imp);
                                                $xml->endElement();
                                                $xml->startElement("baseImpAir");
                                                    $xml->text(number_format($baseiva, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("porcentajeAir");
                                                    $xml->text(number_format($porcentaje_iva, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("valRetAir");
                                                    $xml->text(number_format($retenciones_iva[$f]->cantidadiva, 2, '.', ''));
                                                $xml->endElement();
                                            $xml->endElement();
                                        }
                                    }*/
                                    for($g=0; $g<count($retenciones_renta); $g++){
                                        if($retenciones_renta[$g]->id_factura == $fc[$i]->id_factcompra){
                                            $porcentaje_renta = str_replace(array('%', ' '), '' , $retenciones_renta[$g]->porcentajerenta);;
                                            $xml->startElement("detalleAir");
                                                $xml->startElement("codRetAir");
                                                    $xml->text($retenciones_renta[$g]->cod_imp);
                                                $xml->endElement();
                                                $xml->startElement("baseImpAir");
                                                    $xml->text(number_format($retenciones_renta[$g]->baserenta, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("porcentajeAir");
                                                    $xml->text(number_format($porcentaje_renta, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("valRetAir");
                                                    $xml->text(number_format($retenciones_renta[$g]->cantidadrenta, 2, '.', ''));
                                                $xml->endElement();
                                            $xml->endElement();
                                        }
                                    }
                                $xml->endElement();
                            }


                            $xml->startElement("estabRetencion1");
                                $xml->text($fc[$i]->codigo_establecimiento);
                            $xml->endElement();
                            $xml->startElement("ptoEmiRetencion1");
                                $xml->text($fc[$i]->codigo_pe);
                            $xml->endElement();
                            $xml->startElement("secRetencion1");
                                $xml->text(1);
                            $xml->endElement();
                            $xml->startElement("autRetencion1");
                                $xml->text('000');
                            $xml->endElement();
                            $xml->startElement("fechaEmiRet1");
                                $xml->text(Carbon::parse($fc[$i]->fech_emision)->format('d/m/Y'));
                            $xml->endElement();
                            $xml->startElement("docModificado");
                                $xml->text('00');
                            $xml->endElement();
                            $xml->startElement("estabModificado");
                                $xml->text('000');
                            $xml->endElement();
                            $xml->startElement("ptoEmiModificado");
                                $xml->text('000');
                            $xml->endElement();
                            $xml->startElement("secModificado");
                                $xml->text(0);
                            $xml->endElement();
                            $xml->startElement("autModificado");
                                $xml->text('000');
                            $xml->endElement();

                        $xml->endElement();
                    }
                
            }
            if(count($lc)>=1){
                    for($i=0; $i<count($lc); $i++){
                        $xml->startElement("detalleCompras");
                            $xml->startElement("codSustento");
                                $xml->text(str_pad($lc[$i]->cod_sustento, 2, "0", STR_PAD_LEFT));
                            $xml->endElement();
                            if ($lc[$i]->tipo_identificacion == "Cedula") {
                                $xml->startElement("tpIdProv");
                                $xml->text('02');
                                $xml->endElement();
                            } else if ($lc[$i]->tipo_identificacion == "Ruc") {
                                $xml->startElement("tpIdProv");
                                $xml->text('01');
                                $xml->endElement();
                            } else{
                                $xml->startElement("tpIdProv");
                                $xml->text('03');
                                $xml->endElement();
                            }
                            $xml->startElement("idProv");
                                $xml->text(str_replace(array('.', ',', '-', '_', ' '), '', $lc[$i]->identif_proveedor));
                            $xml->endElement();
                            $xml->startElement("tipoComprobante");
                                $xml->text($lc[$i]->cod_tipcomprob);
                            $xml->endElement();
                            $xml->startElement("tipoProv");
                                if($lc[$i]->tipo_contribuyente == "Persona Natural"){
                                    $xml->text('01');
                                }else{
                                    $xml->text('02');
                                }
                            $xml->endElement();
                            $xml->startElement("denoProv");
                                $xml->text(str_replace(array('.', ',', '-', '_','ñ','Ñ','á','é','í','ó','ú','Á','É','Í','Ó','Ú','&','&amp;'), array(' ',' ',' ',' ','n','N','a','e','i','o','u','A','E','I','O','U','',''), $lc[$i]->nombre_proveedor));
                            $xml->endElement();
                            $xml->startElement("parteRel");
                                $xml->text('NO');
                            $xml->endElement();
                            $xml->startElement("fechaRegistro");
                                $xml->text(Carbon::parse($lc[$i]->fecha_emision)->format('d/m/Y'));
                            $xml->endElement();
                            $xml->startElement("establecimiento");
                                $xml->text(substr(str_replace(array(" ","-"),array("",""),$lc[$i]->descripcion),0,3));
                            $xml->endElement();
                            $xml->startElement("puntoEmision");
                                $xml->text(substr(str_replace(array(" ","-"),array("",""),$lc[$i]->descripcion),3,3));
                            $xml->endElement();
                            $xml->startElement("secuencial");
                                $xml->text(substr(str_replace(array(" ","-"),array("",""),$lc[$i]->descripcion),6,9));
                            $xml->endElement();
                            $xml->startElement("fechaEmision");
                                $xml->text(Carbon::parse($lc[$i]->fecha_emision)->format('d/m/Y'));
                            $xml->endElement();
                            $xml->startElement("autorizacion");
                                $xml->text(str_replace(array("-"," ",".","_"),"",$lc[$i]->nro_autorizacion));
                            $xml->endElement();
                            $xml->startElement("baseNoGraIva");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("baseImponible");
                                $xml->text(number_format($lc[$i]->base - $lc[$i]->iva, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("baseImpGrav");
                                $xml->text(number_format($lc[$i]->iva, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("baseImpExe");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("montoIce");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("montoIva");
                                $xml->text(number_format($lc[$i]->valoriva, 2, '.', ''));
                            $xml->endElement();

                            //si los valores tiene su cantidad guarda en su propio valor
                            $valor10=0;
                            $valor20=0;
                            $valor30=0;
                            $valor50=0;
                            $valor70=0;
                            $valor100=0;
                            for($f=0; $f<count($retenciones_iva_lc); $f++){
                                if($retenciones_iva_lc[$f]->id_liquidacion_compra == $lc[$i]->id_liquidacion_compra){
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva_lc[$f]->porcentajeiva) == "10"){
                                        $valor10 = $retenciones_iva_lc[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva_lc[$f]->porcentajeiva) == "20"){
                                        $valor20 = $retenciones_iva_lc[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva_lc[$f]->porcentajeiva) == "30"){
                                        $valor30 = $retenciones_iva_lc[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva_lc[$f]->porcentajeiva) == "50"){
                                        $valor50 = $retenciones_iva_lc[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva_lc[$f]->porcentajeiva) == "70"){
                                        $valor70 = $retenciones_iva_lc[$f]->cantidadiva;
                                    }
                                    if(str_replace(array('%', ' '), '' ,$retenciones_iva_lc[$f]->porcentajeiva) == "100"){
                                        $valor100 = $retenciones_iva_lc[$f]->cantidadiva;
                                    }
                                }
                            }

                            $xml->startElement("valRetBien10");
                                $xml->text(number_format($valor10, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valRetServ20");
                                $xml->text(number_format($valor20, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valorRetBienes");
                                $xml->text(number_format($valor30, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valRetServ50");
                                $xml->text(number_format($valor50, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valorRetServicios");
                                $xml->text(number_format($valor70, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("valRetServ100");
                                $xml->text(number_format($valor100, 2, '.', ''));
                            $xml->endElement();

                            $xml->startElement("totbasesImpReemb");
                                $xml->text('0.00');
                            $xml->endElement();

                            $xml->startElement("pagoExterior");
                                $xml->startElement("pagoLocExt");
                                    $xml->text('01');
                                $xml->endElement();
                                $xml->startElement("paisEfecPago");
                                    $xml->text('NA');
                                $xml->endElement();
                                $xml->startElement("aplicConvDobTrib");
                                    $xml->text('NA');
                                $xml->endElement();
                                $xml->startElement("pagExtSujRetNorLeg");
                                    $xml->text('NA');
                                $xml->endElement();
                                $xml->startElement("pagoRegFis");
                                    $xml->text('NA');
                                $xml->endElement();
                            $xml->endElement();

                            $xml->startElement("formasDePago");
                                $xml->startElement("formaPago");
                                    if($lc[$i]->valoriva == 0){
                                        $xml->text('01');
                                    }else{
                                        $xml->text(20);
                                    }
                                $xml->endElement();
                            $xml->endElement();
                            $existerenta = 0 ;
                            for($g=0; $g<count($retenciones_renta_lc); $g++){
                                if($retenciones_renta_lc[$g]->id_liquidacion_compra == $lc[$i]->id_liquidacion_compra){
                                    $existerenta = 1;
                                }
                            }
                            if($existerenta == 1){
                                $xml->startElement("air");
                                    /*for($f=0; $f<count($retenciones_iva); $f++){
                                        if($retenciones_iva[$f]->id_factura == $lc[$i]->id_factcompra){
                                            $porcentaje_iva = str_replace(array('%', ' '), '' , $retenciones_iva[$f]->porcentajeiva);
                                            $baseiva =  ($retenciones_iva[$f]->cantidadiva * 100) / $porcentaje_iva;
                                            $xml->startElement("detalleAir");
                                                $xml->startElement("codRetAir");
                                                    $xml->text($retenciones_iva[$f]->cod_imp);
                                                $xml->endElement();
                                                $xml->startElement("baseImpAir");
                                                    $xml->text(number_format($baseiva, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("porcentajeAir");
                                                    $xml->text(number_format($porcentaje_iva, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("valRetAir");
                                                    $xml->text(number_format($retenciones_iva[$f]->cantidadiva, 2, '.', ''));
                                                $xml->endElement();
                                            $xml->endElement();
                                        }
                                    }*/
                                    for($g=0; $g<count($retenciones_renta_lc); $g++){
                                        if($retenciones_renta_lc[$g]->id_liquidacion_compra == $lc[$i]->id_liquidacion_compra){
                                            $porcentaje_renta = str_replace(array('%', ' '), '' , $retenciones_renta_lc[$g]->porcentajerenta);;
                                            $xml->startElement("detalleAir");
                                                $xml->startElement("codRetAir");
                                                    $xml->text($retenciones_renta_lc[$g]->cod_imp);
                                                $xml->endElement();
                                                $xml->startElement("baseImpAir");
                                                    $xml->text(number_format($retenciones_renta_lc[$g]->baserenta, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("porcentajeAir");
                                                    $xml->text(number_format($porcentaje_renta, 2, '.', ''));
                                                $xml->endElement();
                                                $xml->startElement("valRetAir");
                                                    $xml->text(number_format($retenciones_renta_lc[$g]->cantidadrenta, 2, '.', ''));
                                                $xml->endElement();
                                            $xml->endElement();
                                        }
                                    }
                                $xml->endElement();
                            }


                            $xml->startElement("estabRetencion1");
                                $xml->text($lc[$i]->codigo_establecimiento);
                            $xml->endElement();
                            $xml->startElement("ptoEmiRetencion1");
                                $xml->text($lc[$i]->codigo_pe);
                            $xml->endElement();
                            $xml->startElement("secRetencion1");
                                $xml->text(1);
                            $xml->endElement();
                            $xml->startElement("autRetencion1");
                                $xml->text('000');
                            $xml->endElement();
                            $xml->startElement("fechaEmiRet1");
                                $xml->text(Carbon::parse($lc[$i]->fecha_emision)->format('d/m/Y'));
                            $xml->endElement();
                            $xml->startElement("docModificado");
                                $xml->text('00');
                            $xml->endElement();
                            $xml->startElement("estabModificado");
                                $xml->text('000');
                            $xml->endElement();
                            $xml->startElement("ptoEmiModificado");
                                $xml->text('000');
                            $xml->endElement();
                            $xml->startElement("secModificado");
                                $xml->text(0);
                            $xml->endElement();
                            $xml->startElement("autModificado");
                                $xml->text('000');
                            $xml->endElement();

                        $xml->endElement();
                    }
                
            }
            if(count($nc_compra)>=1){
                for($i=0; $i<count($nc_compra); $i++){
                    $xml->startElement("detalleCompras");
                        $xml->startElement("codSustento");
                            $xml->text(str_pad($nc_compra[$i]->cod_sustento, 2, "0", STR_PAD_LEFT));
                        $xml->endElement();
                        if ($nc_compra[$i]->tipo_identificacion == "Cedula") {
                            $xml->startElement("tpIdProv");
                            $xml->text('02');
                            $xml->endElement();
                        } else if ($nc_compra[$i]->tipo_identificacion == "Ruc") {
                            $xml->startElement("tpIdProv");
                            $xml->text('01');
                            $xml->endElement();
                        } else{
                            $xml->startElement("tpIdProv");
                            $xml->text('03');
                            $xml->endElement();
                        }
                        $xml->startElement("idProv");
                            $xml->text(str_replace(array('.', ',', '-', '_', ' '), '', $nc_compra[$i]->identif_proveedor));
                        $xml->endElement();
                        $xml->startElement("tipoComprobante");
                            $xml->text($nc_compra[$i]->cod_tipcomprob);
                        $xml->endElement();
                        $xml->startElement("tipoProv");
                            if($nc_compra[$i]->tipo_contribuyente == "Persona Natural"){
                                $xml->text('01');
                            }else{
                                $xml->text('02');
                            }
                        $xml->endElement();
                        $xml->startElement("denoProv");
                            $xml->text(str_replace(array('.', ',', '-', '_','ñ','Ñ','á','é','í','ó','ú','Á','É','Í','Ó','Ú','&','&amp;'), array(' ',' ',' ',' ','n','N','a','e','i','o','u','A','E','I','O','U','',''), $nc_compra[$i]->nombre_proveedor));
                        $xml->endElement();
                        $xml->startElement("parteRel");
                            $xml->text('NO');
                        $xml->endElement();
                        $xml->startElement("fechaRegistro");
                            $xml->text(Carbon::parse($nc_compra[$i]->fecha_emision)->format('d/m/Y'));
                        $xml->endElement();
                        $xml->startElement("establecimiento");
                            $xml->text(substr(str_replace(array(" ","-"),array("",""),$nc_compra[$i]->descripcion),0,3));
                        $xml->endElement();
                        $xml->startElement("puntoEmision");
                            $xml->text(substr(str_replace(array(" ","-"),array("",""),$nc_compra[$i]->descripcion),3,3));
                        $xml->endElement();
                        $xml->startElement("secuencial");
                            $xml->text(substr(str_replace(array(" ","-"),array("",""),$nc_compra[$i]->descripcion),6,9));
                        $xml->endElement();
                        $xml->startElement("fechaEmision");
                            $xml->text(Carbon::parse($nc_compra[$i]->fecha_emision)->format('d/m/Y'));
                        $xml->endElement();
                        $xml->startElement("autorizacion");
                            $xml->text(str_replace(array("-"," ",".","_"),"",$nc_compra[$i]->descripcion));
                        $xml->endElement();
                        $xml->startElement("baseNoGraIva");
                            $xml->text('0.00');
                        $xml->endElement();
                        $xml->startElement("baseImponible");
                            $xml->text(number_format($nc_compra[$i]->base - $nc_compra[$i]->iva, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("baseImpGrav");
                            $xml->text(number_format($nc_compra[$i]->iva, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("baseImpExe");
                            $xml->text('0.00');
                        $xml->endElement();
                        $xml->startElement("montoIce");
                            $xml->text('0.00');
                        $xml->endElement();
                        $xml->startElement("montoIva");
                            $xml->text(number_format($nc_compra[$i]->valoriva, 2, '.', ''));
                        $xml->endElement();

                        //si los valores tiene su cantidad guarda en su propio valor
                        $valor10=0;
                        $valor20=0;
                        $valor30=0;
                        $valor50=0;
                        $valor70=0;
                        $valor100=0;

                        $xml->startElement("valRetBien10");
                            $xml->text(number_format($valor10, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("valRetServ20");
                            $xml->text(number_format($valor20, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("valorRetBienes");
                            $xml->text(number_format($valor30, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("valRetServ50");
                            $xml->text(number_format($valor50, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("valorRetServicios");
                            $xml->text(number_format($valor70, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("valRetServ100");
                            $xml->text(number_format($valor100, 2, '.', ''));
                        $xml->endElement();

                        $xml->startElement("totbasesImpReemb");
                            $xml->text('0.00');
                        $xml->endElement();

                        $xml->startElement("pagoExterior");
                            $xml->startElement("pagoLocExt");
                                $xml->text('01');
                            $xml->endElement();
                            $xml->startElement("paisEfecPago");
                                $xml->text('NA');
                            $xml->endElement();
                            $xml->startElement("aplicConvDobTrib");
                                $xml->text('NA');
                            $xml->endElement();
                            $xml->startElement("pagExtSujRetNorLeg");
                                $xml->text('NA');
                            $xml->endElement();
                            $xml->startElement("pagoRegFis");
                                $xml->text('NA');
                            $xml->endElement();
                        $xml->endElement();

                        // $xml->startElement("formasDePago");
                        //     $xml->startElement("formaPago");
                        //         if($nc_compra[$i]->valoriva == 0){
                        //             $xml->text('01');
                        //         }else{
                        //             $xml->text(20);
                        //         }
                        //     $xml->endElement();
                        // $xml->endElement();
                        // $existerenta = 0 ;
                        // $xml->startElement("estabRetencion1");
                        //     $xml->text($nc_compra[$i]->codigo_establecimiento);
                        // $xml->endElement();
                        // $xml->startElement("ptoEmiRetencion1");
                        //     $xml->text($nc_compra[$i]->codigo_pe);
                        // $xml->endElement();
                        // $xml->startElement("secRetencion1");
                        //     $xml->text(1);
                        // $xml->endElement();
                        // $xml->startElement("autRetencion1");
                        //     $xml->text('000');
                        // $xml->endElement();
                        // $xml->startElement("fechaEmiRet1");
                        //     $xml->text(Carbon::parse($nc_compra[$i]->fech_emision)->format('d/m/Y'));
                        // $xml->endElement();
                        $xml->startElement("docModificado");
                            $xml->text('01');
                        $xml->endElement();
                        $xml->startElement("estabModificado");
                            $xml->text(substr(str_replace(array(" ","-"),array("",""),$nc_compra[$i]->descripcion),0,3));
                        $xml->endElement();
                        $xml->startElement("ptoEmiModificado");
                            $xml->text(substr(str_replace(array(" ","-"),array("",""),$nc_compra[$i]->descripcion),3,3));
                        $xml->endElement();
                        $xml->startElement("secModificado");
                            $xml->text(substr(str_replace(array(" ","-"),array("",""),$nc_compra[$i]->descripcion),6,9));
                        $xml->endElement();
                        $xml->startElement("autModificado");
                            $xml->text(str_replace(array("-"," ",".","_"),"",$nc_compra[$i]->nro_autorizacion));
                        $xml->endElement();

                    $xml->endElement();
                }
            
            }
            if(count($fc)>=1 || count($lc)>=1 || count($nc_compra)>=1){
                $xml->endElement();
            }
            if(count($fv)>=1 || count($nc_venta)>=1){
                $xml->startElement("ventas");
            }
            if(count($fv)>=1){
                    for($i=0; $i<count($fv); $i++){
                        $xml->startElement("detalleVentas");
                            if ($fv[$i]->tipo_identificacion == "Cédula de Identidad" || $fv[$i]->tipo_identificacion == "Cedula de Identidad") {
                                $xml->startElement("tpIdCliente");
                                $xml->text('05');
                                $xml->endElement();
                            } else if ($fv[$i]->tipo_identificacion == "Ruc") {
                                $xml->startElement("tpIdCliente");
                                $xml->text('04');
                                $xml->endElement();
                            } else if ($fv[$i]->tipo_identificacion == "Pasaporte") {
                                $xml->startElement("tpIdCliente");
                                $xml->text('06');
                                $xml->endElement();
                            } else {
                                $xml->startElement("tpIdCliente");
                                $xml->text('07');
                                $xml->endElement();
                            }
                            $xml->startElement("idCliente");
                                $xml->text($fv[$i]->identificacion);
                            $xml->endElement();
                            if($fv[$i]->tipo_identificacion == "Cédula de Identidad" || $fv[$i]->tipo_identificacion == "Cedula de Identidad" || $fv[$i]->tipo_identificacion == "Ruc" || $fv[$i]->tipo_identificacion == "Pasaporte"){
                                $xml->startElement("parteRelVtas");
                                    $xml->text('NO');
                                $xml->endElement();
                            }
                            if($fv[$i]->tipo_identificacion == "Pasaporte"){
                                $xml->startElement("tipoCliente");
                                    if($fv[$i]->grupo_tributario == "Persona Natural"){
                                        $xml->text('01');
                                    }else if($fv[$i]->grupo_tributario == "Persona Jurídica"){
                                        $xml->text('02');
                                    }else{
                                        $xml->text('01');
                                    }
                                $xml->endElement();
                                $xml->startElement("denoCli");
                                    $xml->text(str_replace(array('.', ',', '-', '_', ' '), '', $fv[$i]->nombrecliente));
                                $xml->endElement();
                            }
                            $xml->startElement("tipoComprobante");
                                $xml->text(18);
                            $xml->endElement();
                            $xml->startElement("tipoEmision");
                                $xml->text('E');
                            $xml->endElement();
                            $xml->startElement("numeroComprobantes");
                                $xml->text(1);
                            $xml->endElement();
                            if($fv[$i]->financiamiento>0){
                                $xml->startElement("baseNoGraIva");
                                $xml->text(number_format($fv[$i]->financiamiento , 2, '.', ''));
                                $xml->endElement();
                            }else{
                                $xml->startElement("baseNoGraIva");
                                $xml->text('0.00');
                                $xml->endElement();
                            }
                            $xml->startElement("baseImponible");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("baseImpGrav");
                                $xml->text(number_format($fv[$i]->base , 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("montoIva");
                                $xml->text(number_format($fv[$i]->iva, 2, '.', ''));
                            $xml->endElement();
                            $xml->startElement("montoIce");
                                $xml->text('0.00');
                            $xml->endElement();
                            $xml->startElement("valorRetIva");
                                if($fv[$i]->r_iva){
                                    $xml->text(number_format($fv[$i]->r_iva, 2, '.', ''));
                                }else{
                                    $xml->text('0.00');
                                }
                            $xml->endElement();
                            $xml->startElement("valorRetRenta");
                                if($fv[$i]->r_renta){
                                    $xml->text(number_format($fv[$i]->r_renta, 2, '.', ''));
                                }else{
                                    $xml->text('0.00');
                                }
                            $xml->endElement();

                            $xml->startElement("formasDePago");
                                $xml->startElement("formaPago");
                                    $xml->text(20);
                                $xml->endElement();
                            $xml->endElement();

                        $xml->endElement();
                    }
                
            }
            
            if(count($nc_venta)>=1){
                for($i=0; $i<count($nc_venta); $i++){
                    $xml->startElement("detalleVentas");
                        if ($nc_venta[$i]->tipo_identificacion == "Cédula de Identidad" || $nc_venta[$i]->tipo_identificacion == "Cedula de Identidad") {
                            $xml->startElement("tpIdCliente");
                            $xml->text('05');
                            $xml->endElement();
                        } else if ($nc_venta[$i]->tipo_identificacion == "Ruc") {
                            $xml->startElement("tpIdCliente");
                            $xml->text('04');
                            $xml->endElement();
                        } else if ($nc_venta[$i]->tipo_identificacion == "Pasaporte") {
                            $xml->startElement("tpIdCliente");
                            $xml->text('06');
                            $xml->endElement();
                        } else {
                            $xml->startElement("tpIdCliente");
                            $xml->text('07');
                            $xml->endElement();
                        }
                        $xml->startElement("idCliente");
                            $xml->text($nc_venta[$i]->identificacion);
                        $xml->endElement();
                        if($nc_venta[$i]->tipo_identificacion == "Cédula de Identidad" || $nc_venta[$i]->tipo_identificacion == "Cedula de Identidad" || $nc_venta[$i]->tipo_identificacion == "Ruc" || $nc_venta[$i]->tipo_identificacion == "Pasaporte"){
                            $xml->startElement("parteRelVtas");
                                $xml->text('NO');
                            $xml->endElement();
                        }
                        if($nc_venta[$i]->tipo_identificacion == "Pasaporte"){
                            $xml->startElement("tipoCliente");
                                if($nc_venta[$i]->grupo_tributario == "Persona Natural"){
                                    $xml->text('01');
                                }else if($nc_venta[$i]->grupo_tributario == "Persona Jurídica"){
                                    $xml->text('02');
                                }else{
                                    $xml->text('01');
                                }
                            $xml->endElement();
                            $xml->startElement("denoCli");
                                $xml->text(str_replace(array('.', ',', '-', '_', ' '), '', $nc_venta[$i]->nombrecliente));
                            $xml->endElement();
                        }
                        $xml->startElement("tipoComprobante");
                            $xml->text("04");
                        $xml->endElement();
                        $xml->startElement("tipoEmision");
                            $xml->text('E');
                        $xml->endElement();
                        $xml->startElement("numeroComprobantes");
                            $xml->text(1);
                        $xml->endElement();
                        $xml->startElement("baseNoGraIva");
                            $xml->text('0.00');
                        $xml->endElement();
                        $xml->startElement("baseImponible");
                            $xml->text('0.00');
                        $xml->endElement();
                        $xml->startElement("baseImpGrav");
                            $xml->text(number_format($nc_venta[$i]->base , 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("montoIva");
                            $xml->text(number_format($nc_venta[$i]->iva, 2, '.', ''));
                        $xml->endElement();
                        $xml->startElement("montoIce");
                            $xml->text('0.00');
                        $xml->endElement();
                        $xml->startElement("valorRetIva");
                        $xml->text('0.00');

                        $xml->endElement();
                        $xml->startElement("valorRetRenta");
                        $xml->text('0.00');

                        $xml->endElement();
                    $xml->endElement();
                }
            
            }
            if(count($fv)>=1 || count($nc_venta)>=1){
                $xml->endElement();
            }
            $xml->startElement("ventasEstablecimiento");
                for($i=0; $i<count($establecimiento); $i++){
                    $xml->startElement("ventaEst");
                        $xml->startElement("codEstab");
                            $xml->text($establecimiento[$i]->codigo);
                        $xml->endElement();
                        $xml->startElement("ventasEstab");
                            $xml->text('0.00');
                        $xml->endElement();
                        $xml->startElement("ivaComp");
                            $xml->text('0.00');
                        $xml->endElement();
                    $xml->endElement();
                }
            $xml->endElement();
            if(count($fv_cancelados)>=1){
                $xml->startElement("anulados");
                    for($i=0; $i<count($fv_cancelados); $i++){
                        $xml->startElement("detalleAnulados");
                            $xml->startElement("tipoComprobante");
                                $xml->text('01');
                            $xml->endElement();
                            $xml->startElement("establecimiento");
                                $xml->text(str_replace(array(" ","-"),array("",""),$fv_cancelados[$i]->codigo));
                            $xml->endElement();
                            $xml->startElement("puntoEmision");
                                $xml->text(str_replace(array(" ","-"),array("",""),$fv_cancelados[$i]->codigope));
                            $xml->endElement();
                            $xml->startElement("secuencialInicio");
                                $xml->text(substr($fv_cancelados[$i]->clave_acceso, -19, -10));
                            $xml->endElement();
                            $xml->startElement("secuencialFin");
                                $xml->text(substr($fv_cancelados[$i]->clave_acceso, -19, -10));
                            $xml->endElement();
                            $xml->startElement("autorizacion");
                                $xml->text(str_replace("-","",$fv_cancelados[$i]->clave_acceso));
                            $xml->endElement();
                        $xml->endElement();
                    }
                $xml->endElement();
            }
            $xml->endElement();
        $xml->endDocument();
    }
    public function generarPDF(Request $request){
        //dd($request);
        $info_reporte=json_decode($request->reporte, true);
        if($info_reporte["id"] == 1){
            $date = "{$request->anio}-{$request->mes}-01";
            $ultimo=date("Y-m-t", strtotime($date));
            // $factura_compra=DB::select("SELECT tipo_comprobante.cod_tipcomprob as  cod_sustento,tipo_comprobante.descrip_tipcomprob as descrip_sustento,count(factura_compra.id_factcompra) as nro_registros,sum(factura_compra.subtotal_0) as bi_tarifa_0,sum(factura_compra.subtotal_12) as bi_tarifa_12,sum(factura_compra.subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(factura_compra.iva_12) as valor_iva,max(id_factcompra) as id_factcompra  
            // from factura_compra
			// LEFT JOIN tipo_comprobante
			// on tipo_comprobante.id_tipcomprobante=factura_compra.id_tipo_comprobante
            // where factura_compra.id_empresa={$request->company} and modo_orden=0 and documento_tributario=1 and date(factura_compra.fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            // GROUP BY tipo_comprobante.id_tipcomprobante
            // UNION
            // SELECT '05' as  cod_sustento,'Nota de Debito' as descrip_sustento,count(id_nota_debito_compra) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva ,0 as id_factcompra
            // from nota_debito_compra
            // where id_empresa={$request->company} and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
            // UNION
            // SELECT '04' as  cod_sustento,'Nota de Credito' as descrip_sustento,count(id_nota_credito_compra) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva,0 as id_factcompra 
            // from nota_credito_compra
            // where id_empresa={$request->company} and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            // ");
            $factura_compra=DB::select("SELECT cod_sustento, max(descrip_sustento) as descrip_sustento,sum(nro_registros) as nro_registros,sum(bi_tarifa_0) as bi_tarifa_0,sum(bi_tarifa_12) as bi_tarifa_12,sum(bi_tarifa_no_iva) as bi_tarifa_no_iva,sum(valor_iva) as valor_iva,max(id) as id_factcompra
                from (
                                                    SELECT tipo_comprobante.cod_tipcomprob as  cod_sustento,tipo_comprobante.descrip_tipcomprob as descrip_sustento,count(factura_compra.												id_factcompra) as nro_registros,sum(factura_compra.subtotal_0) as bi_tarifa_0,sum(factura_compra.subtotal_12) as bi_tarifa_12,sum(											factura_compra.subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(factura_compra.iva_12) as valor_iva,max(id_factcompra) as id  
                                            from factura_compra
                                            LEFT JOIN tipo_comprobante
                                            on tipo_comprobante.id_tipcomprobante=factura_compra.id_tipo_comprobante
                                            where factura_compra.id_empresa={$request->company} and modo_orden=0  and documento_tributario=1 and date(factura_compra.fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                            GROUP BY tipo_comprobante.id_tipcomprobante
                
                    union all
                
                                                SELECT tipo_comprobante.cod_tipcomprob as  cod_sustento,tipo_comprobante.descrip_tipcomprob as descrip_sustento,count(liquidacion_compra.												id_liquidacion_compra) as nro_registros,sum(liquidacion_compra.subtotal_0) as bi_tarifa_0,sum(liquidacion_compra.subtotal_12) as 											bi_tarifa_12,sum(liquidacion_compra.subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(liquidacion_compra.iva_12) as valor_iva,max(											id_liquidacion_compra) as id  
                                            from liquidacion_compra
                                            LEFT JOIN tipo_comprobante
                                            on tipo_comprobante.id_tipcomprobante=liquidacion_compra.id_tipo_comprobante
                                            where liquidacion_compra.id_empresa={$request->company} and documento_tributario=1 and liquidacion_compra.estado=1  and date(liquidacion_compra.fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                            GROUP BY tipo_comprobante.id_tipcomprobante
                ) t
                group by cod_sustento
                UNION
                SELECT '05' as  cod_sustento,'Nota de Debito' as descrip_sustento,count(id_nota_debito_compra) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva ,0 as id_factcompra
                from nota_debito_compra
                where id_empresa={$request->company} and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                UNION
                SELECT '04' as  cod_sustento,'Nota de Credito' as descrip_sustento,count(id_nota_credito_compra) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva,0 as id_factcompra 
                from nota_credito_compra
                where id_empresa={$request->company} and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                ");
            //dd();
            $factura_venta=DB::select("SELECT '18' as  cod_sustento,'Documentos Autorizados en Ventas Excepto ND Y NC ' as descrip_sustento,count(factura.id_factura) as nro_registros,sum(factura.subtotal_0) as bi_tarifa_0,sum(factura.subtotal_12) as bi_tarifa_12,sum(factura.subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(factura.iva_12) as valor_iva 
                from factura
                where factura.id_empresa={$request->company} and factura.estado=1 and factura.modo=1 and date(factura.fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                UNION
                SELECT '06' as  cod_sustento,'Nota de Debito' as descrip_sustento,count(id_nota_debito) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva 
                from nota_debito
                where id_empresa={$request->company} and estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                UNION
                SELECT '04' as  cod_sustento,'Nota de Credito' as descrip_sustento,count(id_nota_credito) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva 
                from nota_credito
                where id_empresa={$request->company} and estado=1 and  date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                ");
            // dd("SELECT '18' as  cod_sustento,'Documentos Autorizados en Ventas Excepto ND Y NC ' as descrip_sustento,count(factura.id_factura) as nro_registros,sum(factura.subtotal_0) as bi_tarifa_0,sum(factura.subtotal_12) as bi_tarifa_12,sum(factura.subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(factura.iva_12) as valor_iva 
            // from factura
            // where factura.id_empresa={$request->company} and factura.estado=1 and date(factura.fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            // UNION
            // SELECT '06' as  cod_sustento,'Nota de Debito' as descrip_sustento,count(id_nota_debito) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva 
            // from nota_debito
            // where id_empresa={$request->company} and estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
            // UNION
            // SELECT '04' as  cod_sustento,'Nota de Credito' as descrip_sustento,count(id_nota_credito) as nro_registros,sum(subtotal_0) as bi_tarifa_0,sum(subtotal_12) as bi_tarifa_12,sum(subtotal_no_obj_iva) as bi_tarifa_no_iva,sum(iva_12) as valor_iva 
            // from nota_credito
            // where id_empresa={$request->company} and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
            // ");
            $anulados=DB::select("SELECT count(id_factura) as nro_registros from factura where id_empresa={$request->company} and estado=0 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') UNION
            SELECT count(id_nota_credito) as nro_registros from nota_credito where id_empresa={$request->company} and estado=0 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') UNION
            SELECT count(id_nota_debito) as nro_registros from nota_debito where id_empresa={$request->company} and estado=0 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            ");
            $retencion_renta=DB::select("SELECT max(cod_imp) as cod_imp,max(descrip_imp) as descrip_imp,max(porcen_imp) as porcen_imp,sum(nro_registo) as nro_registo,sum(base_imponible) as base_imponible,sum(valor_retencion) as valor_retencion 
                from (
                                                SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(baserenta) as base_imponible,sum(cantidadrenta) as valor_retencion,id_imp from retencion_factura_comp
                                                INNER JOIN factura_compra
                                                on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                                INNER JOIN retencion
                                                on retencion.id_retencion=retencion_factura_comp.id_retencion_renta
                                                LEFT JOIN impuesto
                                                on retencion.id_impuesto=impuesto.id_imp
                                                where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                                GROUP BY impuesto.id_imp
                                
                    union all
                
                                                SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_liquidacion_compra.id_liquidacion_compra) as nro_registo,sum(baserenta) as base_imponible,sum(cantidadrenta) as valor_retencion,id_imp from retencion_liquidacion_compra
                                                INNER JOIN liquidacion_compra
                                                on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                                INNER JOIN retencion
                                                on retencion.id_retencion=retencion_liquidacion_compra.id_retencion_renta
                                                LEFT JOIN impuesto
                                                on retencion.id_impuesto=impuesto.id_imp
                                                where liquidacion_compra.id_empresa={$request->company} and liquidacion_compra.estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                                GROUP BY impuesto.id_imp
                ) t
                group by id_imp
                UNION
                SELECT cod_imp,max(descrip_imp) as descrip_imp,max(porcen_imp) as porcen_imp,sum(nro_registo) as nro_registo,sum(base_imponible) as base_imponible,sum(valor_retencion) as valor_retencion
                from (
                                            SELECT '001' as cod_imp, 'Transacciones sin Retencion' as descrip_imp, 0 as porcen_imp,count(id_factcompra) as nro_registo,sum(subtotal_12)+sum(subtotal_0) as base_imponible,0 as valor_retencion
                                            from factura_compra
                                            LEFT JOIN retencion_factura_comp
                                            on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                            where factura_compra.id_empresa={$request->company} and modo_orden=0 and documento_tributario=1 and retencion_factura_comp.id_factura is null and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')

                                
                    union all
                
                                                SELECT '001' as cod_imp, 'Transacciones sin Retencion' as descrip_imp, 0 as porcen_imp,count( liquidacion_compra.id_liquidacion_compra) as nro_registo,sum(subtotal_12)+sum(subtotal_0) as base_imponible,0 as valor_retencion
                                            from liquidacion_compra
                                            LEFT JOIN retencion_liquidacion_compra
                                            on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                            where liquidacion_compra.id_empresa={$request->company} and documento_tributario=1 and liquidacion_compra.estado=1 and retencion_liquidacion_compra.id_liquidacion_compra is null and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                ) t
                group by cod_imp
                ORDER BY cod_imp asc");
            
            $retencion_iva=DB::select("SELECT max(cod_imp) as cod_imp,max(descrip_imp) as descrip_imp,max(porcen_imp) as porcen_imp,sum(nro_registo) as nro_registo,sum(base_imponible) as base_imponible,sum(valor_retencion) as valor_retencion
                from (
                                            SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,id_imp from retencion_factura_comp
                                            INNER JOIN factura_compra
                                            on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                            INNER JOIN retencion
                                            on retencion.id_retencion=retencion_factura_comp.id_retencion_iva
                                            LEFT JOIN impuesto
                                            on retencion.id_impuesto=impuesto.id_imp
                                            where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
                                            GROUP BY impuesto.id_imp

                                
                    union all
                
                                            SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_liquidacion_compra.id_liquidacion_compra) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,id_imp from retencion_liquidacion_compra
                                            INNER JOIN liquidacion_compra
                                            on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                            INNER JOIN retencion
                                            on retencion.id_retencion=retencion_liquidacion_compra.id_retencion_iva
                                            LEFT JOIN impuesto
                                            on retencion.id_impuesto=impuesto.id_imp
                                            where liquidacion_compra.id_empresa={$request->company} and liquidacion_compra.estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
                                            GROUP BY impuesto.id_imp
                ) t
                group by id_imp
                ORDER BY cod_imp asc");
            // dd("SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion from retencion_factura_comp
            // INNER JOIN factura_compra
            // on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            // INNER JOIN retencion
            // on retencion.id_retencion=retencion_factura_comp.id_retencion_iva
            // LEFT JOIN impuesto
            // on retencion.id_impuesto=impuesto.id_imp
            // where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
            // GROUP BY impuesto.id_imp
            // ORDER BY impuesto.cod_imp asc");
            $retencion_renta_venta=DB::select("SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_factura.id_factura) as nro_registo,sum(baserenta) as base_imponible,sum(cantidadrenta) as valor_retencion 
            from retencion_factura
            INNER JOIN factura
            on factura.id_factura=retencion_factura.id_factura
            INNER JOIN retencion
            on retencion.id_retencion=retencion_factura.id_retencion_renta
            LEFT JOIN impuesto
            on retencion.id_impuesto=impuesto.id_imp
            where factura.id_empresa={$request->company} and estado=1 and factura.modo=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
            GROUP BY impuesto.id_imp 
            UNION
            SELECT '332' as cod_imp, 'Otras compras de bienes y servicios no sujetas a retención' as descrip_imp, 0 as porcen_imp,count(factura.id_factura) as nro_registo,sum(subtotal_12)+sum(subtotal_0) as base_imponible,0 as valor_retencion  
            from factura
            LEFT JOIN retencion_factura
            on retencion_factura.id_factura=factura.id_factura
            where factura.id_empresa={$request->company} and estado=1 and factura.modo=1 and retencion_factura.id_retencion_factura is null and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            ORDER BY cod_imp asc");
            $retencion_iva_venta=DB::select("SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_factura.id_factura) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion from retencion_factura
            INNER JOIN factura
            on factura.id_factura=retencion_factura.id_factura
            INNER JOIN retencion
            on retencion.id_retencion=retencion_factura.id_retencion_iva
            LEFT JOIN impuesto
            on retencion.id_impuesto=impuesto.id_imp
            where factura.id_empresa={$request->company} and factura.estado=1 and factura.modo=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
            GROUP BY impuesto.id_imp
            ORDER BY impuesto.cod_imp asc");
            //dd($ultimo);
            $empresa=DB::select("SELECT * from empresa where id_empresa={$request->company}");
            $reportePdf = new generarReportes();
            $strPDF = $reportePdf->ATS_reporte($empresa[0],$date,$ultimo,$factura_compra,$factura_venta,$anulados,$retencion_renta,$retencion_iva,$retencion_renta_venta,$retencion_iva_venta);
        }
        if($info_reporte["id"] == 2){
            $date = "{$request->anio}-{$request->mes}-01";
            $ultimo=date("Y-m-t", strtotime($date));
            // $retencion_renta=DB::select("SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(baserenta) as base_imponible,sum(cantidadrenta) as valor_retencion,impuesto.id_imp 
            // from retencion_factura_comp
            // INNER JOIN factura_compra
            // on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            // INNER JOIN retencion
            // on retencion.id_retencion=retencion_factura_comp.id_retencion_renta
            // LEFT JOIN impuesto
            // on retencion.id_impuesto=impuesto.id_imp
            // where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
            // GROUP BY impuesto.id_imp
            // UNION
            // SELECT '001' as cod_imp, 'Transacciones sin Retencion' as descrip_imp, 0 as porcen_imp,count(id_factcompra) as nro_registo,sum(subtotal_12)+sum(subtotal_0) as base_imponible,0 as valor_retencion,'001' as id_imp
            // from factura_compra
            // LEFT JOIN retencion_factura_comp
            // on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            // where factura_compra.id_empresa={$request->company} and modo_orden=0 and retencion_factura_comp.id_factura is null and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            // ORDER BY cod_imp asc");
            
            $retencion_renta=DB::select("SELECT max(cod_imp) as cod_imp,max(descrip_imp) as descrip_imp,max(porcen_imp) as porcen_imp,sum(nro_registo) as nro_registo,sum(base_imponible) as base_imponible,sum(valor_retencion) as valor_retencion,id_imp 
                from (
                                                SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(baserenta) as base_imponible,sum(cantidadrenta) as valor_retencion,id_imp from retencion_factura_comp
                                                INNER JOIN factura_compra
                                                on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                                INNER JOIN retencion
                                                on retencion.id_retencion=retencion_factura_comp.id_retencion_renta
                                                LEFT JOIN impuesto
                                                on retencion.id_impuesto=impuesto.id_imp
                                                where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                                GROUP BY impuesto.id_imp
                                
                    union all
                
                                                SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_liquidacion_compra.id_liquidacion_compra) as nro_registo,sum(baserenta) as base_imponible,sum(cantidadrenta) as valor_retencion,id_imp from retencion_liquidacion_compra
                                                INNER JOIN liquidacion_compra
                                                on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                                INNER JOIN retencion
                                                on retencion.id_retencion=retencion_liquidacion_compra.id_retencion_renta
                                                LEFT JOIN impuesto
                                                on retencion.id_impuesto=impuesto.id_imp
                                                where liquidacion_compra.id_empresa={$request->company} and liquidacion_compra.estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                                GROUP BY impuesto.id_imp
                ) t
                group by id_imp
                UNION
                SELECT cod_imp,max(descrip_imp) as descrip_imp,max(porcen_imp) as porcen_imp,sum(nro_registo) as nro_registo,sum(base_imponible) as base_imponible,sum(valor_retencion) as valor_retencion,'001' as id_imp
                from (
                                            SELECT '001' as cod_imp, 'Transacciones sin Retencion' as descrip_imp, 0 as porcen_imp,count(id_factcompra) as nro_registo,sum(subtotal_12)+sum(subtotal_0) as base_imponible,0 as valor_retencion
                                            from factura_compra
                                            LEFT JOIN retencion_factura_comp
                                            on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                            where factura_compra.id_empresa={$request->company} and modo_orden=0 and documento_tributario=1 and retencion_factura_comp.id_factura is null and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')

                                
                    union all
                
                                                SELECT '001' as cod_imp, 'Transacciones sin Retencion' as descrip_imp, 0 as porcen_imp,count( liquidacion_compra.id_liquidacion_compra) as nro_registo,sum(subtotal_12)+sum(subtotal_0) as base_imponible,0 as valor_retencion
                                            from liquidacion_compra
                                            LEFT JOIN retencion_liquidacion_compra
                                            on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                            where liquidacion_compra.id_empresa={$request->company} and documento_tributario=1 and retencion_liquidacion_compra.id_liquidacion_compra is null and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                ) t
                group by cod_imp
                ORDER BY cod_imp asc");
                // dd("SELECT max(cod_imp) as cod_imp,max(descrip_imp) as descrip_imp,max(porcen_imp) as porcen_imp,sum(nro_registo) as nro_registo,sum(base_imponible) as base_imponible,sum(valor_retencion) as valor_retencion,id_imp
                // from (
                //                             SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,id_imp from retencion_factura_comp
                //                             INNER JOIN factura_compra
                //                             on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                //                             INNER JOIN retencion
                //                             on retencion.id_retencion=retencion_factura_comp.id_retencion_iva
                //                             LEFT JOIN impuesto
                //                             on retencion.id_impuesto=impuesto.id_imp
                //                             where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
                //                             GROUP BY impuesto.id_imp

                                
                //     union all
                
                //                             SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_liquidacion_compra.id_liquidacion_compra) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,id_imp from retencion_liquidacion_compra
                //                             INNER JOIN liquidacion_compra
                //                             on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                //                             INNER JOIN retencion
                //                             on retencion.id_retencion=retencion_liquidacion_compra.id_retencion_iva
                //                             LEFT JOIN impuesto
                //                             on retencion.id_impuesto=impuesto.id_imp
                //                             where liquidacion_compra.id_empresa={$request->company} and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
                //                             GROUP BY impuesto.id_imp
                // ) t
                // group by id_imp
                // ORDER BY cod_imp asc");
            // $retencion_iva=DB::select("SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,impuesto.id_imp 
            // from retencion_factura_comp
            // INNER JOIN factura_compra
            // on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            // INNER JOIN retencion
            // on retencion.id_retencion=retencion_factura_comp.id_retencion_iva
            // LEFT JOIN impuesto
            // on retencion.id_impuesto=impuesto.id_imp
            // where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
            // GROUP BY impuesto.id_imp
            // ORDER BY impuesto.cod_imp asc");
            $retencion_iva=DB::select("SELECT max(cod_imp) as cod_imp,max(descrip_imp) as descrip_imp,max(porcen_imp) as porcen_imp,sum(nro_registo) as nro_registo,sum(base_imponible) as base_imponible,sum(valor_retencion) as valor_retencion,id_imp
                from (
                                            SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(id_factura) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,id_imp from retencion_factura_comp
                                            INNER JOIN factura_compra
                                            on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                            INNER JOIN retencion
                                            on retencion.id_retencion=retencion_factura_comp.id_retencion_iva
                                            LEFT JOIN impuesto
                                            on retencion.id_impuesto=impuesto.id_imp
                                            where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
                                            GROUP BY impuesto.id_imp

                                
                    union all
                
                                            SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_liquidacion_compra.id_liquidacion_compra) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,id_imp from retencion_liquidacion_compra
                                            INNER JOIN liquidacion_compra
                                            on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                            INNER JOIN retencion
                                            on retencion.id_retencion=retencion_liquidacion_compra.id_retencion_iva
                                            LEFT JOIN impuesto
                                            on retencion.id_impuesto=impuesto.id_imp
                                            where liquidacion_compra.id_empresa={$request->company} and liquidacion_compra.estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')  
                                            GROUP BY impuesto.id_imp
                ) t
                group by id_imp
                ORDER BY cod_imp asc");

            // $factura_compra_renta=DB::select("SELECT factura_compra.descripcion,factura_compra.fech_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,retencion_factura_comp.baserenta,impuesto.porcen_imp as porcentajerenta,retencion_factura_comp.cantidadrenta,impuesto.id_imp,factura_compra.id_factcompra
            //                             from retencion_factura_comp
            //                                         INNER JOIN factura_compra
            //                                         on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            //                                         INNER JOIN proveedor
            //                                         on proveedor.id_proveedor=factura_compra.id_proveedor
            //                                         INNER JOIN retencion
            //                                         on retencion.id_retencion=retencion_factura_comp.id_retencion_renta
            //                                         LEFT JOIN impuesto
            //                                         on retencion.id_impuesto=impuesto.id_imp
            //                             where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            //                                         UNION
            //                             SELECT factura_compra.descripcion,factura_compra.fech_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,subtotal_12+subtotal_0 as baserenta,'0.00' as porcentajerenta,0 as cantidadrenta,'001' as id_imp,factura_compra.id_factcompra
            //                             from factura_compra
            //                                         INNER JOIN proveedor
            //                                         on proveedor.id_proveedor=factura_compra.id_proveedor
            //                                         LEFT JOIN retencion_factura_comp
            //                                         on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            //                             where factura_compra.id_empresa={$request->company} AND modo_orden=0 and retencion_factura_comp.id_factura is null and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            //                                     ORDER BY fecha_emision,nombre asc");
            $factura_compra_renta=DB::select("SELECT factura_compra.descripcion,factura_compra.fech_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,retencion_factura_comp.baserenta,impuesto.porcen_imp as porcentajerenta,retencion_factura_comp.cantidadrenta,impuesto.id_imp,factura_compra.id_factcompra
                                                from retencion_factura_comp
                                                            INNER JOIN factura_compra
                                                            on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                                            INNER JOIN proveedor
                                                            on proveedor.id_proveedor=factura_compra.id_proveedor
                                                            INNER JOIN retencion
                                                            on retencion.id_retencion=retencion_factura_comp.id_retencion_renta
                                                            LEFT JOIN impuesto
                                                            on retencion.id_impuesto=impuesto.id_imp
                                                where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                                            UNION
                                                SELECT factura_compra.descripcion,factura_compra.fech_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,subtotal_12+subtotal_0 as baserenta,'0.00' as porcentajerenta,0 as cantidadrenta,'001' as id_imp,factura_compra.id_factcompra
                                                from factura_compra
                                                            INNER JOIN proveedor
                                                            on proveedor.id_proveedor=factura_compra.id_proveedor
                                                            LEFT JOIN retencion_factura_comp
                                                            on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                                where factura_compra.id_empresa={$request->company} AND modo_orden=0 and documento_tributario=1 and retencion_factura_comp.id_factura is null and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                                            UNION
                                                SELECT liquidacion_compra.descripcion,liquidacion_compra.fecha_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,retencion_liquidacion_compra.baserenta,impuesto.porcen_imp as porcentajerenta,retencion_liquidacion_compra.cantidadrenta,impuesto.id_imp,liquidacion_compra.id_liquidacion_compra as id
                                                from retencion_liquidacion_compra
                                                            INNER JOIN liquidacion_compra
                                                            on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                                            INNER JOIN proveedor
                                                            on proveedor.id_proveedor=liquidacion_compra.id_proveedor
                                                            INNER JOIN retencion
                                                            on retencion.id_retencion=retencion_liquidacion_compra.id_retencion_renta
                                                            LEFT JOIN impuesto
                                                            on retencion.id_impuesto=impuesto.id_imp
                                                where liquidacion_compra.id_empresa={$request->company} and liquidacion_compra.estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                                            UNION
                                                SELECT liquidacion_compra.descripcion,liquidacion_compra.fecha_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,subtotal_12+subtotal_0 as baserenta,'0.00' as porcentajerenta,0 as cantidadrenta,'001' as id_imp,liquidacion_compra.id_liquidacion_compra as id
                                                from liquidacion_compra
                                                            INNER JOIN proveedor
                                                            on proveedor.id_proveedor=liquidacion_compra.id_proveedor
                                                            LEFT JOIN retencion_liquidacion_compra
                                                            on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                                where liquidacion_compra.id_empresa={$request->company} and liquidacion_compra.estado=1 and documento_tributario=1 and retencion_liquidacion_compra.id_liquidacion_compra is null and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                                        ORDER BY fecha_emision,nombre asc");
                                                        
            $factura_compra_iva=DB::select("SELECT factura_compra.descripcion,factura_compra.fech_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,cantidadiva/(retencion.porcen_retencion/100) as baserenta,impuesto.porcen_imp as porcentajerenta,retencion_factura_comp.cantidadiva as cantidadrenta,impuesto.id_imp,factura_compra.id_factcompra
                                        from retencion_factura_comp
                                                    INNER JOIN factura_compra
                                                    on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                                                    INNER JOIN proveedor
                                                    on proveedor.id_proveedor=factura_compra.id_proveedor
                                                    INNER JOIN retencion
                                                    on retencion.id_retencion=retencion_factura_comp.id_retencion_iva
                                                    LEFT JOIN impuesto
                                                    on retencion.id_impuesto=impuesto.id_imp
                                        where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                        UNION
                                        SELECT liquidacion_compra.descripcion,liquidacion_compra.fecha_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,cantidadiva/(retencion.porcen_retencion/100) as baserenta,impuesto.porcen_imp as porcentajerenta,retencion_liquidacion_compra.cantidadiva as cantidadrenta,impuesto.id_imp,liquidacion_compra.id_liquidacion_compra as id_factcompra
                                        from retencion_liquidacion_compra
                                                    INNER JOIN liquidacion_compra
                                                    on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                                                    INNER JOIN proveedor
                                                    on proveedor.id_proveedor=liquidacion_compra.id_proveedor
                                                    INNER JOIN retencion
                                                    on retencion.id_retencion=retencion_liquidacion_compra.id_retencion_iva
                                                    LEFT JOIN impuesto
                                                    on retencion.id_impuesto=impuesto.id_imp
                                        where liquidacion_compra.id_empresa={$request->company} and liquidacion_compra.estado=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                        ORDER BY fecha_emision,nombre asc");
            $retencion_renta_venta=DB::select("SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_factura.id_factura) as nro_registo,sum(baserenta) as base_imponible,sum(cantidadrenta) as valor_retencion,impuesto.id_imp 
                                            from retencion_factura
                                                INNER JOIN factura
                                                on factura.id_factura=retencion_factura.id_factura
                                                INNER JOIN retencion
                                                on retencion.id_retencion=retencion_factura.id_retencion_renta
                                                LEFT JOIN impuesto
                                                on retencion.id_impuesto=impuesto.id_imp
                                            where factura.id_empresa={$request->company} and estado=1 and factura.modo=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                            GROUP BY impuesto.id_imp 
                                            UNION
                                            SELECT '332' as cod_imp, 'Otras compras de bienes y servicios no sujetas a retención' as descrip_imp, 0 as porcen_imp,count(factura.id_factura) as nro_registo,sum(subtotal_12)+sum(subtotal_0) as base_imponible,0 as valor_retencion,'001' as id_imp  
                                            from factura
                                                LEFT JOIN retencion_factura
                                                on retencion_factura.id_factura=factura.id_factura
                                            where factura.id_empresa={$request->company} and estado=1 and retencion_factura.id_retencion_factura is null and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}')
                                            ORDER BY cod_imp asc");
            $retencion_iva_venta=DB::select("SELECT impuesto.cod_imp,impuesto.descrip_imp,impuesto.porcen_imp,count(retencion_factura.id_factura) as nro_registo,sum(cantidadiva/(retencion.porcen_retencion/100)) as base_imponible,sum(cantidadiva) as valor_retencion,impuesto.id_imp 
                                            from retencion_factura
                                                INNER JOIN factura
                                                on factura.id_factura=retencion_factura.id_factura
                                                INNER JOIN retencion
                                                on retencion.id_retencion=retencion_factura.id_retencion_iva
                                                LEFT JOIN impuesto
                                                on retencion.id_impuesto=impuesto.id_imp
                                            where factura.id_empresa={$request->company} and factura.estado=1 and factura.modo=1 and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                                GROUP BY impuesto.id_imp
                                                ORDER BY impuesto.cod_imp asc");
            $factura_venta_renta=DB::select("SELECT factura.clave_acceso as descripcion,factura.fecha_emision as fecha_emision,cliente.nombre as nombre,cliente.identificacion as identificacion,retencion_factura.baserenta,impuesto.porcen_imp as porcentajerenta,retencion_factura.cantidadrenta,impuesto.id_imp,factura.id_factura
                                            from retencion_factura
                                                        INNER JOIN factura
                                                        on factura.id_factura=retencion_factura.id_factura
                                                        INNER JOIN cliente
                                                        on cliente.id_cliente=factura.id_cliente
                                                        INNER JOIN retencion
                                                        on retencion.id_retencion=retencion_factura.id_retencion_renta
                                                        LEFT JOIN impuesto
                                                        on retencion.id_impuesto=impuesto.id_imp
                                            where factura.id_empresa={$request->company} and factura.estado=1 and factura.modo=1 and  date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                            UNION
                                            SELECT factura.clave_acceso as descripcion,factura.fecha_emision as fecha_emision,cliente. nombre,cliente.identificacion,subtotal_12+subtotal_0 as baserenta,'0.00' as porcentajerenta,0 as cantidadrenta,'001' as id_imp,factura.id_factura
                                            from factura
                                                        INNER JOIN cliente
                                                        on cliente.id_cliente=factura.id_cliente
                                                        LEFT JOIN retencion_factura
                                                        on factura.id_factura=retencion_factura.id_factura
                                            where factura.id_empresa={$request->company} AND factura.estado=1 and factura.modo=1 and retencion_factura.id_factura is null and date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                                    ORDER BY fecha_emision,nombre asc");
            $factura_venta_iva=DB::select("SELECT factura.clave_acceso as descripcion,factura.fecha_emision as fecha_emision,cliente.nombre as nombre,cliente.identificacion as identificacion,cantidadiva/(retencion.porcen_retencion/100) as baserenta,impuesto.porcen_imp as porcentajerenta,retencion_factura.cantidadiva as cantidadrenta,impuesto.id_imp,factura.id_factura
                                            from retencion_factura
                                                        INNER JOIN factura
                                                        on factura.id_factura=retencion_factura.id_factura
                                                        INNER JOIN cliente
                                                        on cliente.id_cliente=factura.id_cliente
                                                        INNER JOIN retencion
                                                        on retencion.id_retencion=retencion_factura.id_retencion_iva
                                                        LEFT JOIN impuesto
                                                        on retencion.id_impuesto=impuesto.id_imp
                                            where factura.id_empresa={$request->company} and factura.estado=1 and factura.modo=1 and  date(fecha_emision) BETWEEN date('{$date}') and date('{$ultimo}') 
                                                    ORDER BY fecha_emision,nombre asc");
            // dd("SELECT factura_compra.descripcion,factura_compra.fech_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,retencion_factura_comp.baserenta,impuesto.porcen_imp as porcentajerenta,retencion_factura_comp.cantidadrenta,impuesto.id_imp,factura_compra.id_factcompra
            // from retencion_factura_comp
            //             INNER JOIN factura_compra
            //             on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            //             INNER JOIN proveedor
            //             on proveedor.id_proveedor=factura_compra.id_proveedor
            //             INNER JOIN retencion
            //             on retencion.id_retencion=retencion_factura_comp.id_retencion_renta
            //             LEFT JOIN impuesto
            //             on retencion.id_impuesto=impuesto.id_imp
            // where factura_compra.id_empresa={$request->company} and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            //             UNION
            // SELECT factura_compra.descripcion,factura_compra.fech_emision as fecha_emision,proveedor.nombre_proveedor as nombre,proveedor.identif_proveedor as identificacion,subtotal_12+subtotal_0 as baserenta,'0.00' as porcentajerenta,0 as cantidadrenta,'001' as id_imp,factura_compra.id_factcompra
            // from factura_compra
            //             INNER JOIN proveedor
            //             on proveedor.id_proveedor=factura_compra.id_proveedor
            //             LEFT JOIN retencion_factura_comp
            //             on factura_compra.id_factcompra=retencion_factura_comp.id_factura
            // where factura_compra.id_empresa={$request->company} AND modo_orden=0 and retencion_factura_comp.id_factura is null and date(fech_emision) BETWEEN date('{$date}') and date('{$ultimo}')
            //         ORDER BY fecha_emision,nombre asc");
            $empresa=DB::select("SELECT * from empresa where id_empresa={$request->company}");
            $reportePdf = new generarReportes();
            $strPDF = $reportePdf->ATS_detalle_reporte($empresa[0],$date,$ultimo,$retencion_renta,$retencion_iva,$factura_compra_renta,$factura_compra_iva,$retencion_renta_venta,$retencion_iva_venta,$factura_venta_renta,$factura_venta_iva);
        }
        
    }
}
