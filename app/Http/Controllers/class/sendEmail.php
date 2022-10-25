<?php

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class sendEmail
{
    public function enviarCorreo($tipo, $nombre, $claveAcceso, $email, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa)
    {
        $dominio = env('APP_URL','');
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, utf8_decode('Comprobante Electrónico'));
        $razon_social = DB::select("SELECT * from empresa where id_empresa={$id_empresa}");
        $email_cli = "";
        //if(gettype($email)=='array'){
        $array_cli = [];
        if (gettype($email) == "string") {
            $array_cli = explode(";", $email);
        } else {
            $array_cli = $email;
        }

        //dd($array_cli);
        for ($i = 0; $i < count($array_cli); $i++) {
            $addemail = trim($array_cli[$i]);
            $mail->addAddress($addemail);
        }
        //}
        // else{
        //     dd(gettype($email));
        //     $email_cli=$email;
        //     $mail->addAddress($email_cli);
        // }

        //$mail->addAddress($email);
        //$mail->addAddress("wily2809@hotmail.com");
        $pdflink ="";
        $mail->isHTML(true);
        if ($tipo == 'Factura') {
            $factura_id=DB::select("SELECT * from factura where id_empresa={$id_empresa} and clave_acceso like '%$claveAcceso%'");
            $pdflink =  $dominio . '/api/creacion_factura_venta_pdf/' . $factura_id[0]->id_factura . '/v';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.pdf');
            $carpeta_respuesta_sri = constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/respuestaSRI/' . $claveAcceso . '.xml';
            if (file_exists($carpeta_respuesta_sri)) {
                $mail->addAttachment($carpeta_respuesta_sri);
            } else {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.xml');
            }
            //$mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notacredito') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notadebito') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.xml');
        } else if ($tipo == 'retencion_compra') {
            $retencion_id=DB::select("SELECT * from factura_compra where id_empresa={$id_empresa} and observacion like '%$claveAcceso%'");
            $pdflink =  $dominio . '/api/creacion_retencion_compra_pdf/' . $retencion_id[0]->id_factcompra . '/v/fc';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/retencion_' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notacreditocompra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacreditocompra/' . $claveAcceso . '.pdf');
        } else if ($tipo == 'factura_compra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/facturacompra/' . $claveAcceso . '.pdf');
        } else if ($tipo == 'guia_remision_venta') {
            $guia_id=DB::select("SELECT * FROM guia_remision,factura where guia_remision.id_factura=factura.id_factura and factura.id_empresa={$id_empresa} and guia_remision.clave_acceso like '%{$claveAcceso}%'");
            $pdflink =  $dominio . '/api/creacion_guia_remision_pdf/' . $guia[0]->id_factura . '/v/fv';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.xml');
        }else if ($tipo == 'guia_nota_venta') {
            $guia_id=DB::select("SELECT * FROM guia_remision,nota_venta where guia_remision.id_nota_venta=nota_venta.id_nota_venta and nota_venta.id_empresa={$id_empresa} and guia_remision.clave_acceso like '%{$claveAcceso}%'");
            $pdflink =  $dominio . '/api/creacion_guia_remision_pdf/' . $guia[0]->id_nota_venta . '/v/nv';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.xml');
        }else if ($tipo == 'LiquidacionCompra'){
            $liqid_c_0=DB::select("SELECT * from liquidacion_compra where clave_acceso like '%$claveAcceso%' and id_empresa={$id_empresa}");
            $pdflink =  $dominio . '/api/creacion_liquidacion_compra_pdf/' . $liqid_c_0[0]->id_liquidacion_compra . '/v';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/liquidacion_compra/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/liquidacion_compra/' . $claveAcceso . '.xml');
        }else if($tipo == 'retencion_liquidacion_compra'){
            $liqid_c=DB::select("SELECT * from liquidacion_compra where observacion like '%$claveAcceso%' and id_empresa={$id_empresa}");
            $pdflink =  $dominio . '/api/creacion_retencion_compra_pdf/' . $liqid_c[0]->id_liquidacion_compra . '/v/lc';
            $liqid_c=DB::select("SELECT * from liquidacion_compra where observacion like '%$claveAcceso%' and id_empresa={$id_empresa}");
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/liquidacion_compra/retencion_' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $liqid_c[0]->clave_acceso . '.xml');
        }

        if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo)) {
            $mail->AddEmbeddedImage(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo, 'logocliente');
        } else {
            $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logocliente');
        }
        $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logo1');
        if ($tipo == 'Notacreditocompra') {
            $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Nota Credito Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
        } else {
            if ($tipo == 'guia_nota_venta') {
                $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Nota Venta&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
            } else {
                if($tipo == 'LiquidacionCompra'){
                    $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Liquidacion Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="'.$pdflink.'" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                }else{
                    if($tipo == 'retencion_liquidacion_compra' || $tipo == 'retencion_compra'){
                        $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Retencion&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="'.$pdflink.'" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                    }else{
                        $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Factura&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#FF4500" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="'.$pdflink.'" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                    }
                }
            }
        }
        $final = ucfirst($tipo);
        $mail->Subject = utf8_decode($final . ' ' . $razon_social[0]->razon_social);
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function enviarCorreoNotaCredito($tipo, $nombre, $claveAcceso, $email, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa)
    {
        try {
            //code...
            $correo = trim($empresas->email_empresa);
            $correopass = trim($empresas->password);
            $correoservidor = trim($empresas->servidor_correo);
            $correopuerto = trim($empresas->puerto_correo);
            $correoseguridad = trim($empresas->seguridad_correo);
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $correoservidor;
            $mail->SMTPAuth = true;
            $mail->Username = $correo;
            $mail->Password = $correopass;
            $mail->SMTPSecure = $correoseguridad;
            $mail->Port = $correopuerto;
            $mail->setFrom($correo, utf8_decode('Comprobante Electrónico'));
            $razon_social=DB::select("SELECT * from empresa where id_empresa={$id_empresa}");
            $email_cli = "";
            //if(gettype($email)=='array'){
            $array_cli = [];
            echo(gettype($email));
            echo($email);
            if(gettype($email)=="string"){
                $array_cli = explode(";", $email);
            }else{
                $array_cli = $email;
            }
            echo($array_cli);
            //dd($array_cli);
            for ($i = 0; $i < count($array_cli); $i++) {
                $addemail = trim($array_cli[$i]);
                $mail->addAddress($addemail);
            }
            //}
            // else{
            //     dd(gettype($email));
            //     $email_cli=$email;
            //     $mail->addAddress($email_cli);
            // }

            //$mail->addAddress($email);
            //$mail->addAddress("wily2809@hotmail.com");
            $mail->isHTML(true);
            if ($tipo == 'Factura') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.pdf');
                $carpeta_respuesta_sri = constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/respuestaSRI/' . $claveAcceso . '.xml';
                if (file_exists($carpeta_respuesta_sri)) {
                    $mail->addAttachment($carpeta_respuesta_sri);
                } else {
                    $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.xml');
                }
                //$mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/' . $claveAcceso . '.xml');
            } else if ($tipo == 'Notacredito') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.xml');
            } else if ($tipo == 'Notadebito') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.xml');
            } else if ($tipo == 'retencion_compra') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/retencion_' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $claveAcceso . '.xml');
            } else if ($tipo == 'Notacreditocompra') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacreditocompra/' . $claveAcceso . '.pdf');
            } else if ($tipo == 'factura_compra') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/facturacompra/' . $claveAcceso . '.pdf');
            } else if ($tipo == 'guia_remision_venta') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.xml');
            }else if ($tipo == 'guia_nota_venta') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.xml');
            }else if ($tipo == 'LiquidacionCompra'){
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/liquidacion_compra/' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/liquidacion_compra/' . $claveAcceso . '.xml');
            }else if($tipo == 'retencion_liquidacion_compra'){
                $liqid_c=DB::select("SELECT * from liquidacion_compra where observacion like '%$claveAcceso%' and id_empresa={$id_empresa}");
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/liquidacion_compra/retencion_' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $liqid_c[0]->clave_acceso . '.xml');
            }

            if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo)) {
                $mail->AddEmbeddedImage(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo, 'logocliente');
            } else {
                $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logocliente');
            }
            $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logo1');
            if ($tipo == 'Notacreditocompra') {
                $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Nota Credito Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
            } else {
                if ($tipo == 'guia_nota_venta') {
                    $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Nota Venta&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                } else {
                    if($tipo == 'LiquidacionCompra'){
                        $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Liquidacion Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                    }else{
                        if($tipo == 'retencion_liquidacion_compra' || $tipo == 'retencion_compra'){
                            $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Retencion&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                        }else{
                            $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Factura&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                        }
                                                                
                    }
                    
                }
            }
            $final = ucfirst($tipo);
            $mail->Subject = utf8_decode($final .' ' . $razon_social[0]->razon_social);
            $mail->Body = $bodyContent;
            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
                $err_sri = [
                    "estado" => "Enviado",
                    "estado1" => "Error 11",
                    "mensaje" => "El correo electrónico no se pudo enviar intente mas tarde",
                    "informacion" => "Verifique si los datos del correo de envio como del cliente son correctos"
                ];

                echo "ERROR EN EL CORREO";
                return response()->json($err_sri, 500);
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            //throw $th;
            $err_sri = [
                "estado" => "Enviado",
                "estado1" => "Error 12",
                "mensaje" => "El correo electrónico no se pudo enviar intente mas tarde",
                "informacion" => "Verifique si los datos del correo de envio como del cliente son correctos"
            ];
            return response()->json($err_sri, 500);
        }
        
    }
    public function enviarCorreo_masivo($tipo, $claveAcceso, $email, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa)
    {
        $dominio = env('APP_URL', '');
        //$pdflink =  $dominio . '/api/creacion_factura_venta_pdf/' . $id . '/v';
        //echo($dominio);
        //dd($tipo);
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, utf8_decode('Comprobante Electrónico'));
        $razon_social = DB::select("SELECT * from empresa where id_empresa={$id_empresa}");
        if (gettype($email) == "string") {
            $email = explode(";", $email);
        }
        for ($i = 0; $i < count($email); $i++) {
            $mail->addAddress($email[$i]);
        }
        //$mail->addAddress("wily2809@hotmail.com");
        $pdflink ="";
        $mail->isHTML(true);
        if ($tipo == 'Factura') {
            $factura_id=DB::select("SELECT * from factura where id_empresa={$id_empresa} and clave_acceso like '%$claveAcceso%'");
            $pdflink =  $dominio . '/api/creacion_factura_venta_pdf/' . $factura_id[0]->id_factura . '/v';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.pdf');
            $carpeta_respuesta_sri = constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/respuestaSRI/' . $claveAcceso . '.xml';
            if (file_exists($carpeta_respuesta_sri)) {
                $mail->addAttachment($carpeta_respuesta_sri);
            } else {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.xml');
            }
            //$mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notacredito') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notadebito') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.xml');
        } else if ($tipo == 'retencion_compra') {
            // $mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/retencioncompra/' . $claveAcceso . '.pdf');
            // $mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/retencioncompra/' . $claveAcceso . '.xml');
            $retencion_id=DB::select("SELECT * from factura_compra where id_empresa={$id_empresa} and observacion like '%$claveAcceso%'");
            $pdflink =  $dominio . '/api/creacion_retencion_compra_pdf/' . $retencion_id[0]->id_factcompra . '/v/fc';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/retencion_' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notacreditocompra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacreditocompra/' . $claveAcceso . '.pdf');
        } else if ($tipo == 'factura_compra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/facturacompra/' . $claveAcceso . '.pdf');
        } else if ($tipo == 'guia_remision_venta') {
            $guia_id=DB::select("SELECT * FROM guia_remision,factura where guia_remision.id_factura=factura.id_factura and factura.id_empresa={$id_empresa} and guia_remision.clave_acceso like '%{$claveAcceso}%'");
            $pdflink =  $dominio . '/api/creacion_guia_remision_pdf/' . $guia[0]->id_factura . '/v/fv';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.xml');
        } else if ($tipo == 'guia_nota_venta') {
            $guia_id=DB::select("SELECT * FROM guia_remision,nota_venta where guia_remision.id_nota_venta=nota_venta.id_nota_venta and nota_venta.id_empresa={$id_empresa} and guia_remision.clave_acceso like '%{$claveAcceso}%'");
            $pdflink =  $dominio . '/api/creacion_guia_remision_pdf/' . $guia[0]->id_nota_venta . '/v/nv';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/guia/' . $claveAcceso . '.xml');
        }else if ($tipo == 'LiquidacionCompra'){
            $liqid_c_0=DB::select("SELECT * from liquidacion_compra where clave_acceso like '%$claveAcceso%' and id_empresa={$id_empresa}");
            $pdflink =  $dominio . '/api/creacion_liquidacion_compra_pdf/' . $liqid_c_0[0]->id_liquidacion_compra . '/v';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/liquidacion_compra/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/liquidacion_compra/' . $claveAcceso . '.xml');
        }else if($tipo == 'retencion_liquidacion_compra'){
            $liqid_c=DB::select("SELECT * from liquidacion_compra where observacion like '%$claveAcceso%' and id_empresa={$id_empresa}");
            $pdflink =  $dominio . '/api/creacion_retencion_compra_pdf/' . $liqid_c[0]->id_liquidacion_compra . '/v/lc';
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/liquidacion_compra/retencion_' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $liqid_c[0]->clave_acceso . '.xml');
        }

        if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo)) {
            $mail->AddEmbeddedImage(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo, 'logocliente');
        } else {
            $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logocliente');
        }
        $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logo1');
        if ($tipo == 'Notacreditocompra') {
            $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Nota Credito Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
        } else {
            if ($tipo == 'guia_nota_venta') {
                $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Nota Venta&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
            } else {
                if($tipo == 'LiquidacionCompra'){
                    $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Liquidacion Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="'.$pdflink.'" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                }else{
                    if($tipo == 'retencion_liquidacion_compra' || $tipo == 'retencion_compra'){
                        $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Retencion&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="'.$pdflink.'" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                    }else{
                        $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Factura&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#FF4500" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="'.$pdflink.'" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
                    }
                                                              
                }
            }
        }
        $final = ucfirst($tipo);
        $mail->Subject = utf8_decode($final . ' ' . $razon_social[0]->razon_social);
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function enviarCorreoRetencion($tipo, $nombre, $claveAcceso, $email, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa)
    {
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, utf8_decode('Comprobante Electrónico'));
        $razon_social = DB::select("SELECT * from empresa where id_empresa={$id_empresa}");
        $mail->addAddress($email);
        //$mail->addAddress("wily2809@hotmail.com");
        $mail->isHTML(true);
        if ($tipo == 'Factura') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.pdf');
            $carpeta_respuesta_sri = constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/respuestaSRI/' . $claveAcceso . '.xml';
            if (file_exists($carpeta_respuesta_sri)) {
                $mail->addAttachment($carpeta_respuesta_sri);
            } else {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.xml');
            }
            //$mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notacredito') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notadebito') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.xml');
        } else if ($tipo == 'retencion_compra') {
            // $mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/retencioncompra/' . $claveAcceso . '.pdf');
            // $mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/retencioncompra/' . $claveAcceso . '.xml');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/retencion_' . $claveAcceso . '.pdf');
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $claveAcceso . '.xml');
        } else if ($tipo == 'Notacreditocompra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacreditocompra/' . $claveAcceso . '.pdf');
        } else if ($tipo == 'factura_compra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/facturacompra/' . $claveAcceso . '.pdf');
        }
        if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo)) {
            $mail->AddEmbeddedImage(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo, 'logocliente');
        } else {
            $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logocliente');
        }
        $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logo1');
        if ($tipo == 'Notacreditocompra') {
            $bodyContent = '<div style="background-color:#10163A"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#050b19;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#ffffff;font-size:22px;">Estimado &nbsp; ' . $nombre . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ffffff;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Retencion&nbsp; ' . substr($claveAcceso, 0, 3) . '-' . substr($claveAcceso, 3, 3) . '-' . substr($claveAcceso, 6, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Valor Total de la Retencion:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#ecf0f1;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Si desea visualizar el comprobante en linea da clic aqui:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center" style="padding:20px"> <!--[if !mso]><!-- --> <a href="" target="_blank" style="display:inline-block; text-decoration:none;" class="fluid-on-mobile"> <span> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </td> </tr> </table> </span> </a> <!--<![endif]--> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#ecf0f1;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
        } else {
            $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Retencion&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
        }
        $final = ucfirst($tipo);
        $mail->Subject = utf8_decode($final . ' ' . $razon_social[0]->razon_social);
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function enviarCorreocompra($tipo, $nombre, $claveAcceso, $autorizacionfactura, $email, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa)
    {
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, utf8_decode('Comprobante Electrónico'));
        $razon_social = DB::select("SELECT * from empresa where id_empresa={$id_empresa}");
        $mail->addAddress($email);
        $mail->isHTML(true);
        if ($tipo == 'Notacreditocompra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacreditocompra/' . $claveAcceso . '.pdf');
        } else if ($tipo == 'Notadebitocompra') {
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacreditocompra/' . $claveAcceso . '.pdf');
        }
        /*$bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
        Le informamos que su comprobante electrónico ha sido emitido exitosamente y
        se encuentra adjunto al presente correo. " . $email);*/
        if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo)) {
            $mail->AddEmbeddedImage(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo, 'logocliente');
        } else {
            $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logocliente');
        }
        $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logo1');
        $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:20px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado &nbsp; ' . $nombre . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Factura&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor de la Nota Credito Compra:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FFFFFF"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
        $final = ucfirst($tipo);
        $mail->Subject = utf8_decode($final . ' ' . $razon_social[0]->razon_social);
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function enviarCorreopruebas($empresas)
    {
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Prueba de correo');
        //$mail->addAddress($email);
        $mail->addAddress("wily2809@hotmail.com");
        $mail->isHTML(true);
        $mail->Subject = 'Prueba de correo';
        $mail->Body = 'si funciona';
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Enviado';
        }
    }
    public function enviarCorreopruebasdata($data)
    {
        $correo = trim($data->email_empresa);
        $pass = trim($data->password);
        $servidor = trim($data->servidor_correo);
        $puerto = trim($data->puerto_correo);
        $seguridad = trim($data->seguridad_correo);
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $servidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $pass;
        $mail->SMTPSecure = $seguridad;
        $mail->Port = $puerto;
        $mail->setFrom($correo, 'Prueba de correo');

        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Prueba de correo';
        $mail->Body = 'Envio de correo de prueba';
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Enviado';
        }
    }
    public function enviarProforma($empresas, $factura, $nombre, $tipo)
    {
        //TECHCOMP ruc 1792684706001
        $fecha = Carbon::now();
        if($empresas->ruc_empresa=='1792684706001'){
            $correo = trim($empresas->email_empresa);
            $correopass = trim($empresas->password);
            $correoservidor = trim($empresas->servidor_correo);
            $correopuerto = trim($empresas->puerto_correo);
            $correoseguridad = trim($empresas->seguridad_correo);
            $id_empresa = trim($empresas->id_empresa);
            //$addemail = trim($empresas->email);
            $addemail = "";

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPDebug = 1;
            $mail->Host = $correoservidor;
            $mail->SMTPAuth = true;
            $mail->Username = $correo;
            $mail->Password = $correopass;
            $mail->SMTPSecure = $correoseguridad;
            $mail->Port = $correopuerto;
            $mail->setFrom($correo, 'Envio de Proforma ' . date("d/m/Y", strtotime($empresas->fecha_emision)));
            if ($tipo == 1) {
                $emails = explode(";", $empresas->email);
            } else {
                $emails = $empresas->email;
            }

            for ($i = 0; $i < count($emails); $i++) {
                $addemail = trim($emails[$i]);
                $mail->addAddress($addemail);
            }

            $proforma_nro="";
                if($empresas->codigo<10){
                    $proforma_nro="COT-TECH-".date("Y", strtotime(($empresas->fecha_emision)))."-000".$empresas->codigo;
                }else{
                    if($empresas->codigo<100){
                        $proforma_nro="COT-TECH-".date("Y", strtotime(($empresas->fecha_emision)))."-00".$empresas->codigo;
                    }else{
                        if($empresas->codigo<1000){
                            $proforma_nro="COT-TECH-".date("Y", strtotime(($empresas->fecha_emision)))."-0".$empresas->codigo;
                        }else{
                            $proforma_nro="COT-TECH-".date("Y", strtotime(($empresas->fecha_emision)))."-".$empresas->codigo;
                        }
                    }
                }
            $mail->isHTML(true);
            $valor=number_format($empresas->valor_total,2,".",",");
            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . "/comprobantes/proforma/proforma-{$empresas->identificacion}-{$empresas->fecha_emision}" . '.pdf');
            if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $empresas->logo)) {
                $mail->AddEmbeddedImage(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $empresas->logo, 'logocliente');
            } else {
                $mail->AddEmbeddedImage('images/logo/tech.png', 'logocliente');
            }
            $mail->AddEmbeddedImage('images/logo/tech.png', 'logo1');
            // $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
            // Le informamos que su Proforma1 ha sido emitida exitosamente y
            // se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
            $bodyContent = '
                        <div style="background-color:#ECF0F1">
                        <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr> 
                            <td valign="top" align="left"> 
                                <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                    <tr> 
                                        <td width="100%"> 
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;">
                                                <tr> 
                                                    <td align="center" width="100%">
                                                        <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                                                        <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px">
                                                            <tr> 
                                                                <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                    <tr> 
                                                                        <td valign="top"> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> 
                                                                                        <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="color:#000000;font-size:22px;font-weight:50;">' . strtoupper(utf8_decode($empresas->nombre_empresa)) . '</span>
                                                                                            </p> 
                                                                                        </div> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" align="center"> 
                                                                                        <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> 
                                                                                        <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> 
                                                                                            <tr> 
                                                                                                <td valign="top" align="center">
                                                                                                    <img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" />
                                                                                                </td> 
                                                                                            </tr> 
                                                                                        </table> 
                                                                                        <!--[if gte mso 9]></td></tr></table><![endif]--> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td style="padding:10px"> 
                                                                                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> 
                                                                                            <tr> 
                                                                                                <td> 
                                                                                                </td> 
                                                                                            </tr> 
                                                                                        </table> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> 
                                                                                        <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="color:#000000;font-weight:50;">Fecha de emisi&oacute;n: ' . date("d/m/Y", strtotime($empresas->fecha_emision)) . ' </span>
                                                                                            </p> 
                                                                                        </div> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> 
                                                                                        <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> 
                                                                                            <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;">
                                                                                                <span style="color:#000000;font-weight:50;">Estimado Cliente</span>
                                                                                                
                                                                                            </p> 
                                                                                            <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;">
                                                                                                
                                                                                                <span style="color:#000000;font-weight:600;">'.utf8_decode($nombre).'</span>
                                                                                            </p> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="color:#000000;font-weight:50;">Le informamos que su cotizaci&oacute;n ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span>
                                                                                            </p> 
                                                                                        </div> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td style="padding:10px"> 
                                                                                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> 
                                                                                            <tr> 
                                                                                                <td> 
                                                                                                </td> 
                                                                                            </tr> 
                                                                                        </table> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> 
                                                                                        <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="color:#000000;font-weight:50;">'.utf8_decode("COTIZACIÓN No.").' &nbsp; ' .$proforma_nro . ' </span>
                                                                                            </p> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="color:#000000;font-weight:50;">Valor:</span>
                                                                                            </p> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="font-size:48px;">
                                                                                                    <span style="color:#000000;font-weight:50;">&#36; ' . $valor . ' </span>
                                                                                                </span>
                                                                                            </p> 
                                                                                        </div> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td style="padding:10px"> 
                                                                                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> 
                                                                                            <tr> 
                                                                                                <td> 
                                                                                                </td> 
                                                                                            </tr> 
                                                                                        </table> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> 
                                                                                        <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> 
                                                                                            <div style="display:none; mso-hide: none;"> 
                                                                                                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> 
                                                                                                    <tr> 
                                                                                                        <td align="center" style="padding:15px"> 
                                                                                                            <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> 
                                                                                                                <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> 
                                                                                                                    <font style="color:#ffffff;" class="button"> 
                                                                                                                        <span>Visualizar Factura</span> 
                                                                                                                    </font> 
                                                                                                                </span> 
                                                                                                            </a> 
                                                                                                        </td> 
                                                                                                    </tr> 
                                                                                                </table> 
                                                                                            </div> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td style="padding:10px"> 
                                                                                        <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                            <tr> 
                                                                                                <td> 
                                                                                                </td> 
                                                                                            </tr> 
                                                                                        </table> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> 
                                                                                        <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="color:#000000;font-weight:50;">Generado por TECHCOMP SOLUTIONS</span>
                                                                                            </p> 
                                                                                            <p style="padding: 0; margin: 0;text-align: center;">
                                                                                                <span style="font-size:12px;">
                                                                                                    <span style="color:#000000;font-weight:50;">Este mensaje es generado autom&aacute;ticamente</span>
                                                                                                </span>
                                                                                            </p> 
                                                                                        </div> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
                                                                                <tr> 
                                                                                    <td valign="top" align="center"> 
                                                                                        <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> 
                                                                                        <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> 
                                                                                            <tr> 
                                                                                                <td valign="top" align="center">
                                                                                                    <img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" />
                                                                                                    <span style="color:#000000;font-weight:50;">
                                                                                                        <a href="https://sokai.com.ec/">WWW.TECHCOMPSOLUTIONS.COM</a>
                                                                                                        <br> 0991410077 - 0999310611
                                                                                                    </span>
                                                                                                </td> 
                                                                                            </tr> 
                                                                                        </table> 
                                                                                        <!--[if gte mso 9]></td></tr></table><![endif]--> 
                                                                                    </td> 
                                                                                </tr> 
                                                                            </table> 
                                                                        </td> 
                                                                    </tr> 
                                                                </table> 
                                                            </td> 
                                                        </tr> 
                                                    </table> 
                                                    <!--[if gte mso 9]></td></tr></table><![endif]--> 
                                                </td> 
                                            </tr> 
                                        </table> 
                                    </td> 
                                </tr> 
                            </table> 
                        </td> 
                    </tr> 
                </table> 
            </div>';
            $mail->Subject = 'Proforma ' . $empresas->nombre_empresa;
            $mail->Body = $bodyContent;
            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                return true;
            }
        }else{
            $correo = trim($empresas->email_empresa);
            $correopass = trim($empresas->password);
            $correoservidor = trim($empresas->servidor_correo);
            $correopuerto = trim($empresas->puerto_correo);
            $correoseguridad = trim($empresas->seguridad_correo);
            $id_empresa = trim($empresas->id_empresa);
            //$addemail = trim($empresas->email);
            $addemail = "";

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPDebug = 1;
            $mail->Host = $correoservidor;
            $mail->SMTPAuth = true;
            $mail->Username = $correo;
            $mail->Password = $correopass;
            $mail->SMTPSecure = $correoseguridad;
            $mail->Port = $correopuerto;
            $mail->setFrom($correo, 'Envio de Proforma ' . $fecha);
            if ($tipo == 1) {
                $emails = explode(";", $empresas->email);
            } else {
                $emails = $empresas->email;
            }

            for ($i = 0; $i < count($emails); $i++) {
                $addemail = trim($emails[$i]);
                $mail->addAddress($addemail);
            }


            $mail->isHTML(true);

            $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/proforma/' . $factura . '.pdf');

            $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
            Le informamos que su Proforma ha sido emitida exitosamente y
            se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
            $mail->Subject = 'Proforma ' . $empresas->nombre_empresa;
            $mail->Body = $bodyContent;
            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                return true;
            }
        }
        
    }
    public function enviarOrdenCompra($empresas, $factura, $nombre, $email, $tipo)
    {

        $fecha = Carbon::now();
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $id_empresa = trim($empresas->id_empresa);
        //$addemail = trim($empresas->email);
        $addemail = "";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de Orden Compra ' . $fecha);
        if ($tipo == 1) {
            $emails = explode(";", $email);
        } else {
            $emails = $email;
        }

        for ($i = 0; $i < count($emails); $i++) {
            $addemail = trim($emails[$i]);
            $mail->addAddress($addemail);
        }


        $mail->isHTML(true);

        $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/compras/orden_compra/' . $factura . '.pdf');

        $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
        Le informamos que su Orden compra ha sido emitida exitosamente y
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
        $mail->Subject = 'Orden compra ' . $empresas->nombre_empresa;
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function enviarNotaVenta($empresas, $factura, $nombre, $email, $tipo)
    {

        $fecha = Carbon::now();
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $id_empresa = trim($empresas->id_empresa);
        //$addemail = trim($empresas->email);
        $addemail = "";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de Nota Venta ' . $fecha);
        if ($tipo == 1) {
            $emails = explode(";", $email);
        } else {
            $emails = $email;
        }

        for ($i = 0; $i < count($emails); $i++) {
            $addemail = trim($emails[$i]);
            $mail->addAddress($addemail);
        }


        $mail->isHTML(true);

        //$mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/compras/orden_compra/' . $factura . '.pdf');
        $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notaVenta/' . $factura . '.pdf');
        //$mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/guia/' . $claveAcceso . '.xml');

        $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
        Le informamos que su Nota de Venta ha sido emitida exitosamente y
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
        $mail->Subject = 'Nota de Venta ' . $empresas->nombre_empresa;
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function enviarCtasPagar($empresas, $factura, $nombre, $tipo)
    {
        $fecha = Carbon::now();

        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $id_empresa = trim($empresas->id_empresa);
        $addemail = trim($factura);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de Cuentas por Pagar ' . $fecha);
        $mail->addAddress($addemail);
        $mail->isHTML(true);

        $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/compras/cuentas_pagar/cuenta_por_pagar_' . $tipo . '.pdf');

        $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
        Le informamos que su Cuenta por Pagar ha sido emitida exitosamente y
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
        $mail->Subject = 'Cuentas Por Pagar';
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function enviarAsientos($empresas, $factura, $nombre, $tipo, $nombre_doc)
    {
        $fecha = Carbon::now();

        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $id_empresa = trim($empresas->id_empresa);
        $addemail = trim($factura);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de ' . $nombre_doc . ' ' . $fecha);
        $mail->addAddress($addemail);
        $mail->isHTML(true);

        $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/contabilidad/asientos_contables/' . $tipo . '.pdf');

        $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
        Le informamos que su " . $nombre_doc . " ha sido emitida exitosamente y
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
        $mail->Subject = $nombre_doc;
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function  enviarCtasCobrar($empresas, $factura, $nombre, $tipo)
    {
        $fecha = Carbon::now();

        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $id_empresa = trim($empresas->id_empresa);
        $addemail = trim($factura);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de Cuentas por Cobrar ' . $fecha);
        $mail->addAddress($addemail);
        $mail->isHTML(true);

        $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/facturacion/cuentas_cobrar/cuenta_por_cobrar_' . $tipo . '.pdf');

        $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br>
        Le informamos que su Cuenta por Cobrar ha sido emitida exitosamente y
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
        $mail->Subject = 'Cuentas Por Cobrar';
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
    public function Papeleta_Individual($empresas)
    {
        $fecha = $empresas->fechrol;
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo);
        $id_empresa = trim($empresas->id_empresa);
        $addemail = trim($empresas->email);
        // 'sistemas@stbtechnology.ec', 'Rock091410077*', 'mail.stbtechnology.ec', '465', 'ssl'

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de Papeleta Individual al Nombre de ' . $empresas->primer_nombre . ' ' . $empresas->apellido_paterno . ' ' . 'Fecha ' . $fecha);
        $mail->addAddress($addemail);
        $mail->isHTML(true);

        $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $empresas->primer_nombre . ' ' . $empresas->apellido_paterno . "</bold><br>
        Le informamos que su Pago del mes de " . $fecha . " ha sido emitida exitosamente y
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: " . $correo);
        $mail->Subject = 'Papeleta Individual  Fecha' . $fecha;
        $mail->Body = $bodyContent;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
}
