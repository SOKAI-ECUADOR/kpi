<?php
include 'lib/fpdf/fpdf.php';
include_once 'PDFPapeleta.php';
include_once 'myPDF.php';
include 'lib/barcode-php1/class/BCGcode128.barcode.php';
//include 'lib/barcode-php1/class/BCGColor.php';
include 'lib/barcode-php1/class/BCGDrawing.php';
//include 'lib/barcode-php1/class/BCGFontFile.php';
include 'sendEmail.php';

include_once getenv("FILE_CONFIG_PHP");
use Illuminate\Support\Facades\DB;

class generarPDF {
    public function facturaPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa, $id_factura){
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        //$pdf->Cell(20);
        if($imagen){
            if (file_exists(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen)) {
                if($id_empresa==36){
                    $pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 20, 10, 75, 30);
                }else{
                    $pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 20, 10, 80, 30);
                }
                
            }
        }
        $em_sel = DB::select("SELECT em.telefono, es.urlweb, em.email_empresa, em.leyenda, em.email_facturacion FROM empresa em INNER JOIN establecimiento es ON em.id_empresa=es.id_empresa WHERE em.id_empresa = $id_empresa");
        //variables de empresa que emite
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(11, 41);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 4, utf8_decode('Dirección Matriz: ' . $document->infoTributaria->dirMatriz), 0, 2, 'L', 0);
        if($em_sel[0]->email_facturacion){
            if($em_sel[0]->email_facturacion!='null'){
                $pdf->Cell(29, 4, utf8_decode('Email: ' . $em_sel[0]->email_facturacion), 0, 2, 'L', 0);
            }
        }else{
            if($em_sel[0]->email_facturacion!='null'){
                $pdf->Cell(29, 4, utf8_decode('Email: ' . $em_sel[0]->email_empresa), 0, 2, 'L', 0);
            }
        }
        if($em_sel[0]->telefono>0){
            $pdf->Cell(29, 5, utf8_decode('Teléfono: ' . $em_sel[0]->telefono), 0, 2, 'L', 0);
        }else{
            $pdf->Cell(29, 5, utf8_decode('Teléfono: Sin teléfono'), 0, 2, 'L', 0);
        }
        $pdf->SetFont('Helvetica', '', 10);
        if($em_sel[0]->urlweb || $em_sel[0]->urlweb !="null"){
            $pdf->Cell(96, 4, utf8_decode($em_sel[0]->urlweb), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Helvetica', '', 8);
        if ($document->infoFactura->obligadoContabilidad == 'SI') {$contabilidad = "SI";} else {$contabilidad = "NO";}
        $pdf->SetFont('Helvetica', '', 7.9);
        if($em_sel[0]->leyenda){
            if($em_sel[0]->leyenda==2){ 
                $pdf->Cell(29, 3,'AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001', 0, 2, 'L', 0);
                $pdf->Cell(29, 3, 'Y REGIMEN MICROEMPRESA', 0, 2, 'L', 0);
            }else{
                if($em_sel[0]->leyenda==3){
                    $pdf->Cell(29, 3,'AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001', 0, 2, 'L', 0);
                    $pdf->Cell(29, 3, 'Y REGIMEN RIMPE', 0, 2, 'L', 0);
                }else{
                    $pdf->Cell(29, 4, utf8_decode($em_sel[0]->leyenda), 0, 2, 'L', 0);
                }
                
            }
        }

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(88, 6, utf8_decode('FACTURA '), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode('No. ') . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: ') . $fecha_actual, 0, 2, 'L', 0);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(88, 5, utf8_decode('AMBIENTE: ' . $ambiente), 0, 2, 'L', 0);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(88, 5, utf8_decode('EMISIÓN: ' . $emision), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $pdf->SetXY(111, 61);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($claveAcceso, $id_empresa);
        $pdf->image(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/codigosbarras/codigo_' . $claveAcceso . '.png', null, null, 88, 7);
        
        $cli_telefono = '';
        $cli_guias = '';
        $emp_vendedor = '';
        $infoAdicional = "";
        $correo = "";
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                }
            }
        }
        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->RoundedRect(10, 72, 190, 27, 2, '1234', 'D');
        $pdf->Cell(110, 5, utf8_decode('Razón Social / Nombres y Apellidos: ' . $document->infoFactura->razonSocialComprador), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('RUC / CI.: ' . $document->infoFactura->identificacionComprador), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Correo: ' . $correo), 0, 1, 'L', 0); //ojo
        $pdf->Cell(110, 5, utf8_decode('Fecha de Emisión: ' . $document->infoFactura->fechaEmision), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Usuario: ' . $emp_vendedor), 0, 1, 'L', 0); //ojo
        $pdf->Cell(110, 5, utf8_decode('Dirección: ' . $document->infoFactura->direccionComprador), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Teléfonos: ' . $cli_telefono), 0, 0, 'L', 0); //ojo
        $pdf->Cell(29, 5, utf8_decode('Guías de Remisión: ' . $cli_guias), 0, 0, 'L', 0); //ojo

        //tabla de productos
        $pdf->SetXY(10, 101);
        $pdf->SetFont('Helvetica', 'B', 6);
        //header de tabla
        $pdf->Cell(15, 8, utf8_decode('Código'), 1, 0, 'C', 0);
        $pdf->Cell(15, 8, utf8_decode('Cant'), 1, 0, 'C', 0);
        $pdf->Cell(100, 8, utf8_decode('Descripción'), 1, 0, 'C', 0);
        $pdf->Cell(15, 8, utf8_decode('Prec. Unitario'), 1, 0, 'C', 0);
        $pdf->Cell(15, 8, utf8_decode('Ice'), 1, 0, 'C', 0);
        $pdf->Cell(15, 8, utf8_decode('Descuento'), 1, 0, 'C', 0);
        $pdf->Cell(15, 8, utf8_decode('Prec. Total'), 1, 0, 'C', 0);
        $pdf->Ln();
        //
        //rellenado de campos

        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;
        $propina = 0;
        $subtotal_t = 0;
        $descuento_t = 0;

        $pdf->SetFont('Helvetica', '', 6);
        $detalles = DB::select("SELECT det.*, pr.cod_principal, pr.total_ice AS valorice, iva.codigo AS codigo_iva FROM detalle det INNER JOIN producto pr ON det.id_producto = pr.id_producto INNER JOIN iva ON iva.id_iva = det.id_iva WHERE id_factura = $id_factura");
        for($i=0; $i<count($detalles); $i++){
            $pdf->Cell(15, 6, $detalles[$i]->cod_principal, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detalles[$i]->cantidad, 1, 0, 'R', 0);
            $pdf->Cell(100, 6, substr(utf8_decode($detalles[$i]->nombre),0,80), 1, 0, 'L', 0);
            $pdf->Cell(15, 6, number_format(floatval($detalles[$i]->precio), 2), 1, 0, 'R', 0);
            $pdf->Cell(15, 6, number_format(floatval($detalles[$i]->valorice * $detalles[$i]->cantidad), 2), 1, 0, 'R', 0);
            if ($detalles[$i]->descuento) {
                if($detalles[$i]->p_descuento == 1){
                    $pdf->Cell(15, 6, number_format($detalles[$i]->descuento, 2, '.', ''), 1, 0, 'R', 0);   
                }else{
                    if(isset($detalles[$i]->descuento)){
                        $pdf->Cell(15, 6, number_format((($detalles[$i]->precio * $detalles[$i]->cantidad * $detalles[$i]->descuento)/100), 2, '.', ''), 1, 0, 'R', 0); 
                    }else{
                        $pdf->Cell(15, 6, 0, 1, 0, 'R', 0); 
                    }
                }
            } else {
                $pdf->Cell(15, 6, 0, 1, 0, 'R', 0); 
            }

            if($detalles[$i]->p_descuento == 1){
                $pdf->Cell(15, 6, number_format((($detalles[$i]->precio * $detalles[$i]->cantidad) - $detalles[$i]->descuento), 2, '.', ''), 1, 0, 'R', 0);  
            }else{
                if(isset($detalles[$i]->descuento)){
                    $pdf->Cell(15, 6, number_format(($detalles[$i]->precio * $detalles[$i]->cantidad) - (($detalles[$i]->precio * $detalles[$i]->cantidad * $detalles[$i]->descuento)/100), 2, '.', ''), 1, 0, 'R', 0);
                }else{
                    $pdf->Cell(15, 6, number_format(($detalles[$i]->precio * $detalles[$i]->cantidad), 2, '.', ''), 1, 0, 'R', 0);   
                }
            }
            $pdf->Ln();   
            
            if($detalles[$i]->p_descuento == 1){
                $descuento_t = number_format($detalles[$i]->descuento, 2, '.', '');     
            }else{
                if(isset($detalles[$i]->descuento)){
                    $descuento_t = number_format((($detalles[$i]->precio * $detalles[$i]->cantidad * $detalles[$i]->descuento)/100), 2, '.', '');
                }else{
                    $descuento_t = number_format(0, 2, '.', '');
                }
            }

            $subtotal_t += ($detalles[$i]->cantidad * $detalles[$i]->precio) - $descuento_t;


            if ($detalles[$i]->codigo_iva == 0) {
                $subtotal0 += ($detalles[$i]->cantidad * $detalles[$i]->precio) - $descuento_t;
            }else if ($detalles[$i]->codigo_iva == 2) {
                $subtotal12 += ($detalles[$i]->cantidad * $detalles[$i]->precio) - $descuento_t;
                if($detalles[$i]->p_descuento == 1){
                    if(isset($descuento_t)){
                        $iva += (((($detalles[$i]->precio * $detalles[$i]->cantidad) - $descuento_t)+($detalles[$i]->valorice * $detalles[$i]->cantidad))*0.12);
                    }else{
                        $iva += ((($detalles[$i]->precio * $detalles[$i]->cantidad)+($detalles[$i]->valorice * $detalles[$i]->cantidad))*0.12);
                    }
                }else{
                    if(isset($descuento_t)){
                        $iva += (((($detalles[$i]->precio * $detalles[$i]->cantidad) - (($detalles[$i]->precio * $detalles[$i]->cantidad * $descuento_t)/100))+($detalles[$i]->valorice * $detalles[$i]->cantidad))*0.12);
                    }else{
                        $iva += ((($detalles[$i]->precio * $detalles[$i]->cantidad)+($detalles[$i]->valorice * $detalles[$i]->cantidad))*0.12);
                    }
                }
            }else if ($detalles[$i]->codigo_iva == 6) {
                $subtotal_no_impuesto += ($detalles[$i]->cantidad * $detalles[$i]->precio) - $descuento_t;
            }else if ($detalles[$i]->codigo_iva == 7) {
                $subtotal_no_iva += ($detalles[$i]->cantidad * $detalles[$i]->precio) - $descuento_t;
            }

            if($detalles[$i]->valorice){
                $ice += ($detalles[$i]->valorice * $detalles[$i]->cantidad);
            }

        }

        /*
            foreach ($document->detalles->detalle as $a => $b) {
                $pdf->Cell(25, 6, $b->codigoPrincipal, 1, 0, 'C', 0);
                $pdf->Cell(15, 6, $b->cantidad, 1, 0, 'R', 0);
                $pdf->Cell(75, 6, $b->descripcion, 1, 0, 'L', 0);
                $pdf->Cell(25, 6, number_format(floatval($b->precioUnitario), 2), 1, 0, 'R', 0);
                $pdf->Cell(25, 6, number_format(floatval(0), 2), 1, 0, 'R', 0);
                $pdf->Cell(25, 6, $b->descuento, 1, 0, 'R', 0);
                $pdf->Cell(25, 6, $b->precioTotalSinImpuesto, 1, 0, 'R', 0);
                $pdf->Ln();
            }
            */

            // $ejeX = 65;
            // $ejeX = $ejeX + 20;
            // $pdf->SetXY(10, $ejeX);
        foreach ($document->infoFactura->pagos->pago as $e => $f) {
            if ($f->formaPago == '01') {
                $formaPago = 'Sin utilizacion del sistema financiero';
            }
            if ($f->formaPago == '15') {
                $formaPago = 'Compensacion de deudas';
            }
            if ($f->formaPago == '16') {
                $formaPago = 'Tarjeta debito';
            }
            if ($f->formaPago == '17') {
                $formaPago = 'Dinero Electronico';
            }
            if ($f->formaPago == '18') {
                $formaPago = 'Tarjeta Prepago';
            }
            if ($f->formaPago == '19') {
                $formaPago = 'Tarjeta de credito';
            }
            if ($f->formaPago == '20') {
                $formaPago = 'Otros con utilizacion del sistema financiero';
            }
            if ($f->formaPago == '21') {
                $formaPago = 'Endoso de titulos';
            }
        }

        /*foreach ($document->infoFactura->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                $iva = $b->valor;
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = $b->baseImponible;
                    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_impuesto = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_no_iva = $b->baseImponible;
                }
            }
            if ($b->codigo == 3) {
                $ice = $b->valor;
            }
            if ($b->codigo == 5) {
                $IRBPNR = $b->valor;
            }
        }*/
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($subtotal12, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, 'PAGOS:', 'LTR', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL 0%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($subtotal0, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('FORMA DE PAGO SRI: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($formaPago), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($subtotal_no_impuesto, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('TOTAL: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($f->total), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($subtotal_no_iva, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('PLAZO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($f->plazo), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($subtotal_t, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('UNID. DE TIEMPO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($f->unidadTiempo), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'DESCUENTO', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $document->infoFactura->totalDescuento, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('FORMA DE PAGO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode(''), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($ice, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(50, 6, '12%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($iva, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($IRBPNR, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'PROPINA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, number_format($propina, 2, '.', ''), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $document->infoFactura->importeTotal, 1, 1, 'R', 0);
        if($id_empresa == 1){
            $pdf->Ln();
            $pdf->Ln();
            $pdf->SetFont('Helvetica', 'B', 8);

            $pdf->Cell(95, 3, '_____________________', 0, 0, 'C', 0);
            $pdf->Cell(95, 3, '_____________________', 0, 1, 'C', 0);
            $pdf->Cell(95, 6, 'FIRMA AUTORIZADA', 0, 0, 'C', 0);
            $pdf->Cell(95, 6, 'FIRMA COMPRADOR', 0, 1, 'C', 0);

            $pdf->Ln();
            $pdf->Ln();
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->Cell(25, 3, utf8_decode('Recibí conforme la mercadería detallada en la presente factura, cuyo valor Debo y Pagaré con cheque cruzado a'), 0, 1, 'L', 0);
            $pdf->Cell(25, 3, utf8_decode('nombre de HUGO SOLIS GOMEZJURADO'), 0, 1, 'L', 0);
        }
        
        $pdf->Output(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $valor = $email->enviarCorreo('Factura', $document->infoFactura->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
    }
    public function notaCreditoPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa){
        $pdf = new PDF_MC_Table('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen)) {
            $pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 20, 10, 80, 30);
        }
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'L', 0);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        if ($document->infoNotaCredito->obligadoContabilidad == 'SI') {

            $contabilidad = "SI"; 
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $pdf->SetXY(111, 55);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($claveAcceso, $id_empresa);
        $pdf->image(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/codigosbarras/codigo_' . $claveAcceso . '.png', null, null, 88, 7);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('NOTA DE CRÉDITO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        $cli_correo = '';
        $cli_telefono = '';
        $infoAdicional = "";
        $correo_cli = "";
        $direccion_cli="";
        $telefono_cli="";
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo_cli = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                    if($b == 'Telefono'){
                        $telefono_cli=$a;
                    }
                    if($b == 'Direccion'){
                        $direccion_cli=$a;
                    }
                }
            }
        }
        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->RoundedRect(10, 72, 190, 29, 2, '1234', 'D');
        $pdf->Cell(51, 5, utf8_decode('Razón Social / Nombres y Apellidos: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoNotaCredito->razonSocialComprador), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(21, 5, utf8_decode('Identificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(110, 5, utf8_decode($document->infoNotaCredito->identificacionComprador), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(11, 5, utf8_decode('Fecha: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, utf8_decode($document->infoNotaCredito->fechaEmision), 0, 1, 'L', 0);
        //línea
        $pdf->Line(20, 85, 190, 85);
        //datos del comprobante devuelto
        $tipo_comprobante ='';
        try {
            $tipo_comprobante = 'FACTURA '.$document->infoNotaCredito->numDocModificado;
        } catch (\Throwable $th) {
            $tipo_comprobante = $document->infoNotaCredito->numDocModificado;
        } 
        $pdf->SetXY(10, 86);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(90, 5, utf8_decode('Comprobante que se modifica: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(40, 5, utf8_decode($tipo_comprobante), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(65, 5, utf8_decode('Fecha de Emisión (Comprobante a modificar): '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoNotaCredito->fechaEmisionDocSustento), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(35, 5, utf8_decode('Razón de Modificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoNotaCredito->motivo), 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 103);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(15, 8, utf8_decode('Código'), 1, 'C', 1);
        $pdf->SetXY(25, 103);
        $pdf->MultiCell(15, 4, utf8_decode('Código Auxiliar'), 1, 'C', 1);
        $pdf->SetXY(40, 103);
        $pdf->MultiCell(15, 8, utf8_decode('Cantidad'), 1, 'C', 1);
        $pdf->SetXY(55, 103);
        $pdf->MultiCell(86, 8, utf8_decode('Descripción'), 1, 'C', 1);
        // $pdf->SetXY(90, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(107, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(124, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(141, 103);
        $pdf->MultiCell(19, 4, utf8_decode('Precio Unitario'), 1, 'C', 1);
        $pdf->SetXY(160, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Descuento'), 1, 'C', 1);
        $pdf->SetXY(180, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Precio Total'), 1, 'C', 1);
        $pdf->SetWidths(array(15,15,15,86,19,20,20));
        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $subt = 0.0;
        foreach ($document->detalles->detalle as $a => $b) {
            $data=array(
                $b->codigoInterno."Centrado",
                $b->codigoAdicional."Centrado",
                $b->cantidad."Centrado",
                utf8_decode($b->descripcion),
                number_format(floatval($b->precioUnitario), 2)."Derecha",
                $b->descuento."Derecha",
                number_format(floatval($b->precioTotalSinImpuesto),2)."Derecha"
            );
            // $pdf->Cell(15, 6, $b->codigoInterno, 1, 0, 'C', 0);
            // $pdf->Cell(15, 6, $b->codigoAdicional, 1, 0, 'R', 0);
            // $pdf->Cell(15, 6, $b->cantidad, 1, 0, 'L', 0);
            // $pdf->Cell(86, 6, $b->descripcion, 1, 0, 'L', 0);
            // // $pdf->Cell(17, 6, '', 1, 0, 'C', 0);
            // // $pdf->Cell(17, 6, '', 1, 0, 'R', 0);
            // // $pdf->Cell(17, 6, '', 1, 0, 'L', 0);
            // $pdf->Cell(19, 6, number_format(floatval($b->precioUnitario), 2), 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $b->descuento, 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $b->precioTotalSinImpuesto, 1, 0, 'R', 0);
            // $pdf->Ln();
            $pdf->RowNTCFactura($data, 10);
        }
        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;
        $propina = 0;
        $pdf->Ln();
        $fac_forma = '';
        $total_ntc=0;
        foreach ($document->infoNotaCredito->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                $iva += $b->valor;
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 += $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 += $b->baseImponible;
                    //    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_impuesto += $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_no_iva += $b->baseImponible;
                }
                $total_ntc+= $b->baseImponible+$b->valor;
            }
            if ($b->codigo == 3) {
                $ice += $b->valor;
                $total_ntc+= $b->baseImponible+$b->valor;
            }
            if ($b->codigo == 5) {
                $IRBPNR += $b->valor;
                $total_ntc+= $b->baseImponible+$b->valor;
            }
        }
        
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, utf8_decode('Información Adicional:'), 'LTR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal12, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Dirección: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($direccion_cli), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL IVA 0%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal0, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Teléfono: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($telefono_cli), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal_no_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Email: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($correo_cli), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subt, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->infoNotaCredito->totalSinImpuestos, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $ice, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IVA 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $iva, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $IRBPNR, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $total_ntc, 1, 1, 'R', 0);
        
        
        $pdf->Output(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $email->enviarCorreoNotaCredito('Notacredito', $document->infoNotaCredito->razonSocialComprador, $claveAcceso, $correo_cli, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
        return "bien";

    }
    public function notaCreditoEjemploPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa){
        $pdf = new PDF_MC_Table('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen)) {
            $pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 20, 10, 80, 30);
        }
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($document->razon_social), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(96, 6, utf8_decode($document->razon_social), 0, 2, 'L', 0);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($document->direccion_empresa), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($document->direccion), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        if ($document->obligado_contabilidad == 'SI') {

            $contabilidad = "SI"; 
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, date("d/m/Y H:i:s", strtotime($document->fecha_factura)), 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->tipo_emision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 5, $document->clave_acceso, 0, 2, 'L', 0);
        $pdf->SetXY(111, 55);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($document->clave_acceso, $id_empresa);
        $pdf->image(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/codigosbarras/codigo_' . $document->clave_acceso . '.png', null, null, 88, 7);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->ruc_empresa, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('NOTA DE CRÉDITO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $document->cod_estab . $document->cod_pto . $document->secuencia, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($document->clave_acceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        $cli_correo = '';
        $cli_telefono = '';
        $infoAdicional = "";
        $correo = "";
        // foreach ($document->infoAdicional->campoAdicional as $a) {
        //     foreach ($a->attributes() as $b) {
        //         if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
        //             $correo = $a;
        //             $infoAdicional .= $b . ': ' . $a . "\n";
        //         } else {
        //             $infoAdicional .= $b . ': ' . $a . "\n";
        //         }
        //     }
        // }
        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->RoundedRect(10, 72, 190, 29, 2, '1234', 'D');
        $pdf->Cell(51, 5, utf8_decode('Razón Social / Nombres y Apellidos: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->nombre), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(21, 5, utf8_decode('Identificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(110, 5, utf8_decode($document->identificacion), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(11, 5, utf8_decode('Fecha: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, date("d/m/Y", strtotime($document->fecha_emision)), 0, 1, 'L', 0);
        //línea
        $pdf->Line(20, 85, 190, 85);
        //datos del comprobante devuelto
        $tipo_comprobante = 'FACTURA '.substr($document->autorizacionfactura,0,3)."-".substr($document->autorizacionfactura,3,3)."-".substr($document->autorizacionfactura,6,9);
        $pdf->SetXY(10, 86);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(90, 5, utf8_decode('Comprobante que se modifica: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(40, 5, utf8_decode($tipo_comprobante), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(65, 5, utf8_decode('Fecha de Emisión (Comprobante a modificar): '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, date("d/m/Y", strtotime($document->fechaAutorizacion)), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(35, 5, utf8_decode('Razón de Modificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->motivo), 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 103);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(15, 8, utf8_decode('Código'), 1, 'C', 1);
        $pdf->SetXY(25, 103);
        $pdf->MultiCell(15, 4, utf8_decode('Código Auxiliar'), 1, 'C', 1);
        $pdf->SetXY(40, 103);
        $pdf->MultiCell(15, 8, utf8_decode('Cantidad'), 1, 'C', 1);
        $pdf->SetXY(55, 103);
        $pdf->MultiCell(86, 8, utf8_decode('Descripción'), 1, 'C', 1);
        //$pdf->SetXY(90, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(107, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(124, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
         $pdf->SetXY(141, 103);
        $pdf->MultiCell(19, 4, utf8_decode('Precio Unitario'), 1, 'C', 1);
        $pdf->SetXY(160, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Descuento'), 1, 'C', 1);
        $pdf->SetXY(180, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Precio Total'), 1, 'C', 1);
        $pdf->SetWidths(array(15,15,15,86,19,20,20));
        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $subt = 0.0;
        foreach ($claveAcceso as $b) {
            $data=array(
                $b->id_detalle_nota_credito."Centrado",
                $b->id_detalle_nota_credito."Centrado",
                $b->cantidad."Centrado",
                utf8_decode($b->nombre),
                number_format(floatval($b->precio), 2)."Derecha",
                $b->descuento."Derecha",
                $b->total."Derecha"
            );
            // $pdf->Cell(15, 6, 17, 1, 0, 'C', 0);
            // $pdf->Cell(15, 6, 17, 1, 0, 'R', 0);
            // $pdf->Cell(15, 6, $b->cantidad, 1, 0, 'L', 0);
            // $pdf->Cell(86, 6, $b->nombre, 1, 0, 'L', 0);
            // // $pdf->Cell(17, 6, '', 1, 0, 'C', 0);
            // // $pdf->Cell(17, 6, '', 1, 0, 'R', 0);
            // // $pdf->Cell(17, 6, '', 1, 0, 'L', 0);
            // $pdf->Cell(19, 6, number_format(floatval($b->precio), 2), 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $b->descuento, 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $b->total, 1, 0, 'R', 0);
            // $pdf->Ln();
            $pdf->RowNTCFactura($data, 10);
        }
        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;
        $propina = 0;
        $pdf->Ln();
        $fac_forma = '';
        foreach ($claveAcceso as $b) {
            // if ($b->codigo == 2) {
            //     $iva = $b->valor;
            //     if ($b->codigoPorcentaje == 0) {
            //         $subtotal0 = $b->baseImponible;
            //     }
            //     if ($b->codigoPorcentaje == 2) {
            //         $subtotal12 = $b->baseImponible;
            //         //    $iva = $b->valor;
            //     }
            //     if ($b->codigoPorcentaje == 6) {
            //         $subtotal_no_impuesto = $b->baseImponible;
            //     }
            //     if ($b->codigoPorcentaje == 7) {
            //         $subtotal_no_iva = $b->baseImponible;
            //     }
            // }
            // if ($b->codigo == 3) {
            //     $ice = $b->valor;
            // }
            // if ($b->codigo == 5) {
            //     $IRBPNR = $b->valor;
            // }
        }
        $head_prd=$pdf->GetY();
        $pdf->SetY($head_prd);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, utf8_decode('Información Adicional:'), 'LTR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->subtotal_12, 1, 1, 'R', 0);
        $head_dir_x=$pdf->GetX();
        $pdf->Cell(20, 6, utf8_decode('Dirección: '), 'L', 0, 'L', 0);
        $head_dir_y=$pdf->GetY();
        if($pdf->GetStringWidth(utf8_decode($document->direccion_cliente))>95){
            $pdf->MultiCell(95, 3, utf8_decode($document->direccion_cliente), 'R','L', 0);
        }else{
            $pdf->Cell(95, 6, utf8_decode($document->direccion_cliente), 'R', 0, 'L', 0);
        }
        if($pdf->GetStringWidth(utf8_decode($document->direccion_cliente))>95){
            $pdf->SetXY(125,$head_dir_y);
        }
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL IVA 0%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->subtotal_0, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Teléfono: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($document->telefono), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->subtotal_no_obj_iva, 1, 1, 'R', 0);
        $head_no_iva=$pdf->GetY();
        $pdf->Cell(20, 6, utf8_decode('Email: '), 'L', 0, 'L', 0);
        if($pdf->GetStringWidth(utf8_decode($document->direccion_cliente))>95){
            $pdf->MultiCell(95, 3, utf8_decode($document->email), 'R','L', 0);
        }else{
            $pdf->Cell(95, 6, utf8_decode($document->email), 'R', 0, 'L', 0);
        }
        if($pdf->GetStringWidth(utf8_decode($document->direccion_cliente))>95){
            $pdf->SetXY(125,$head_no_iva);
        }
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format(0,2), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->subtotal_sin_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->valor_ice, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IVA 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->iva_12, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->valor_irbpnr, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->valor_total, 1, 1, 'R', 0);
        
        
        $pdf->Output(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $document->clave_acceso . '.pdf', 'F');
        return $pdf->Output( "nota_credito ".$document->clave_acceso.".pdf", "D");

    }
    public function notaDebitoPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa){
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen)) {
            $pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 20, 10, 80, 30);
        }
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'L', 0);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        if ($document->infoNotaDebito->obligadoContabilidad == 'SI') {

            $contabilidad = "SI"; 
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $pdf->SetXY(111, 55);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($claveAcceso, $id_empresa);
        $pdf->image(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/codigosbarras/codigo_' . $claveAcceso . '.png', null, null, 88, 7);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('NOTA DE DÉBITO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        $cli_correo = '';
        $cli_telefono = '';
        $infoAdicional = "";
        $correo = "";
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                }
            }
        }
        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->RoundedRect(10, 72, 190, 29, 2, '1234', 'D');
        $pdf->Cell(51, 5, utf8_decode('Razón Social / Nombres y Apellidos: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoNotaDebito->razonSocialComprador), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(21, 5, utf8_decode('Identificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(110, 5, utf8_decode($document->infoNotaDebito->identificacionComprador), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(11, 5, utf8_decode('Fecha: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, utf8_decode($document->infoNotaDebito->fechaEmision), 0, 1, 'L', 0);
        //línea
        $pdf->Line(20, 85, 190, 85);
        //datos del comprobante devuelto
        $tipo_comprobante = 'FACTURA';
        $pdf->SetXY(10, 86);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Comprobante que se modifica: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(40, 5, utf8_decode($tipo_comprobante), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Fecha de Emisión (Comprobante a modificar): '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Razón de Modificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode(''), 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 103);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(48, 8, utf8_decode('Comprobante modifica'), 1, 'C', 1);
        $pdf->SetXY(58, 103);
        $pdf->MultiCell(48, 8, utf8_decode('N° doc. modificado'), 1, 'C', 1);
        $pdf->SetXY(106, 103);
        $pdf->MultiCell(48, 8, utf8_decode('Fecha Emision'), 1, 'C', 1);
        $pdf->SetXY(154, 103);
        $pdf->MultiCell(48, 8, utf8_decode('Razon modificación'), 1, 'C', 1);

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $subt = 0.0;
        if ($document->infoNotaDebito->codDocModificado == "01") {
            $pdf->Cell(48, 6, "FACTURA", 1, 0, "L");
        } else {
            $pdf->Cell(48, 6, $document->infoNotaDebito->codDocModificado, 1, 0, "L");
        }
        $pdf->Cell(48, 10, $document->infoNotaDebito->numDocModificado, 1, 0, "L");
        $pdf->Cell(48, 10, $document->infoNotaDebito->fechaEmisionDocSustento, 1, 0, "L");
        foreach ($document->motivos->motivo as $a => $b) {
            $pdf->Cell(48, 10, $b->razon, 1, 0, "C", true);
        }
        $pdf->Ln();
        /*foreach ($document->detalles->detalle as $a => $b) {
            $pdf->Cell(15, 6, $b->codigoAdicional, 1, 0, 'R', 0);
            $pdf->Cell(15, 6, $b->cantidad, 1, 0, 'L', 0);
            $pdf->Cell(35, 6, $b->descripcion, 1, 0, 'R', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'R', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'L', 0);
            $pdf->Cell(19, 6, number_format(floatval($b->precioUnitario), 2), 1, 0, 'R', 0);
            $pdf->Cell(20, 6, $b->descuento, 1, 0, 'R', 0);
            $pdf->Cell(20, 6, $b->precioTotalSinImpuesto, 1, 0, 'R', 0);
            $pdf->Ln();
        }*/

        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;
        $propina = 0;
        $pdf->Ln();
        $fac_forma = '';
        foreach ($document->infoNotaDebito->impuestos->impuesto as $a => $b) {
            if ($b->codigo == 2) {
                $iva = number_format(floatval($b->valor), 2);
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = number_format(floatval($b->baseImponible), 2);
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = number_format(floatval($b->baseImponible), 2);
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_impuesto = number_format(floatval($b->baseImponible), 2);
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_no_iva = number_format(floatval($b->baseImponible), 2);
                }
            }
            if ($b->codigo == 3) {
                $ice = number_format(floatval($b->valor), 2);
            }
            if ($b->codigo == 5) {
                $IRBPNR = number_format(floatval($b->valor), 2);
            }
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, utf8_decode('Información Adicional:'), 'LTR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal12, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Dirección: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($document->infoNotaDebito->direccionComprador), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL IVA 0%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal0, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Teléfono: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($cli_telefono), 'R', 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $subtotal_no_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Email: '), 'L', 0, 'L', 0);
        $pdf->Cell(90, 6, utf8_decode($infoAdicional), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subt, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->infoNotaDebito->totalDescuento, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $ice, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IVA 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $iva, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $IRBPNR, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->infoNotaDebito->valorTotal, 1, 1, 'R', 0);
        
        
        $pdf->Output(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $email->enviarCorreo('Notadebito', $document->infoNotaDebito->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
        return "bien";

    }
    public function comprobanteRetencionPDF($document, $claveAcceso,$id_empresa,$imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa){
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        //$pdf->Cell(20);
        $pdf->SetXY(20, 15);
        if (file_exists(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen)) {
            $pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 20, 8, 75, 30);
        }
        $re_empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = $id_empresa");

        //variables de empresa que emite
        $emp_dir_sucursal = '';
        $emp_contri_especial = '';

        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($emp_dir_sucursal), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(36, 5, utf8_decode('Contribuyente Especial Nro.: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($emp_contri_especial), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        if ($document->infoCompRetencion->obligadoContabilidad == 'SI') {
            $contabilidad = "SI";
        } else {
            $contabilidad = "NO";
        }
        
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);
        if($re_empresa[0]->leyenda==2){
            $pdf->Cell(60, 5, 'AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN MICROEMPRESA', 0, 0, 'L', 0);
        }else{
            if($re_empresa[0]->leyenda==3){
                $pdf->Cell(60, 5, 'AGENTE DE RETENCION RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN RIMPE', 0, 0, 'L', 0);
            }else{
                if($re_empresa[0]->leyenda!="null"){
                    $pdf->Cell(60, 5, $re_empresa[0]->leyenda, 0, 0, 'L', 0);
                }
            }
            
        }


        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $this->generarCodigoBarras($claveAcceso,$id_empresa);
        $pdf->image(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/codigosbarras/codigo_'.$claveAcceso.'.png', null, null, 88, 11);
        $pdf->SetXY(111, 65);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'C', 0);
        $correo = "";
        $direccion = "";
        $telefono = "";
        $serie1="";
        $serie2="";
        $documento="";
        
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    //$infoAdicional .= $b . ': ' . $a . "\n";
                }
                //  else {
                //     $infoAdicional .= $b . ': ' . $a . "\n";
                // }
                if($b== 'Dirección' || $b == 'direccion' || $b== 'Direccion'){
                    $direccion = $a;
                }
                if($b== 'Teléfono' || $b == 'telefono' || $b== 'Telefono'){
                    $telefono = $a;
                }
                if($b== 'Número de factura' || $b== 'numero factura'){
                    // $serie1=substr($a,0,3);
                    // $serie2=substr($a,3,3);
                    // $documento=substr($a,6,15);
                }
            }
        }
        $nro_factura="";
        foreach ($document->impuestos->impuesto as $a => $b) {
            $nro_factura=$b->numDocSustento;
        }
        if($nro_factura!=="" || $nro_factura!==null){
            $serie1=substr($nro_factura,0,3);
            $serie2=substr($nro_factura,3,3);
            $documento=substr($nro_factura,6,15);
        }
        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('COMPROBANTE DE RETENCIÓN'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $document->infoTributaria->estab."-".$document->infoTributaria->ptoEmi."-".$document->infoTributaria->secuencial, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $infoAdicional = "";
        

        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->RoundedRect(10, 72, 190, 17, 2, '1234', 'D');
        // $pdf->Cell(110, 5, utf8_decode('Razón Social / Nombres y Apellidos: ' . $document->infoCompRetencion->razonSocialSujetoRetenido), 0, 0, 'L', 0);
        // $pdf->Cell(29, 5, utf8_decode('Identificación: ' . $document->infoCompRetencion->identificacionSujetoRetenido), 0, 1, 'L', 0);
        // $pdf->Cell(110, 5, utf8_decode('Fecha de Emisión: ' . $document->infoCompRetencion->fechaEmision), 0, 0, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Razón Social / Nombres y Apellidos: ' . $document->infoCompRetencion->razonSocialSujetoRetenido), 0, 0, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Identificación:        ' ).$document->infoCompRetencion->identificacionSujetoRetenido, 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Dirección:                                           ' . $direccion), 0, 0, 'L', 0);
        $pdf->Cell(39, 5, utf8_decode('Correo:                  '. $correo) , 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Teléfono:                                            ' ).$telefono, 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Fecha de Emisión:' ).$document->infoCompRetencion->fechaEmision, 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 91);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(25, 8, utf8_decode('Comprobante'), 1, 'C', 1);
        $pdf->SetXY(35, 91);
        $pdf->MultiCell(30, 8, utf8_decode('Número'), 1, 'C', 1);
        $pdf->SetXY(65, 91);
        $pdf->MultiCell(25, 8, utf8_decode('Fecha Emisión'), 1, 'C', 1);
        $pdf->SetXY(90, 91);
        $pdf->MultiCell(20, 4, utf8_decode('Ejercicio Fiscal'), 1, 'C', 1);
        $pdf->SetXY(110, 91);
        $pdf->MultiCell(25, 8, utf8_decode('Base Imponible'), 1, 'C', 1);
        $pdf->SetXY(135, 91);
        $pdf->MultiCell(20, 8, utf8_decode('Impuesto'), 1, 'C', 1);
        $pdf->SetXY(155, 91);
        $pdf->MultiCell(20, 4, utf8_decode('Porcentaje de Retención'), 1, 'C', 1);
        $pdf->SetXY(175, 91);
        $pdf->MultiCell(25, 8, utf8_decode('Valor Retenido'), 1, 'C', 1);
        //$pdf->Ln();

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $total = 0;
        foreach ($document->impuestos->impuesto as $a => $b) {
            if($b->codigo == 2 && $b->baseImponible>0){
                if ($b->codDocSustento = '01') {
                    $pdf->Cell(25, 6, 'FACTURA', 1, 0, "C", 0);
                } else {
                    $pdf->Cell(25, 6, $b->codDocSustento, 1, 0, "C", 0);
                }
                $pdf->Cell(30, 6, $serie1."-".$serie2."-".$documento, 1, 0, 'R', 0);
                $pdf->Cell(25, 6, $b->fechaEmisionDocSustento, 1, 0, 'C', 0);
                $pdf->Cell(20, 6, date("Y"), 1, 0, 'C', 0);
                $pdf->Cell(25, 6, $b->baseImponible, 1, 0, 'R', 0);
                if ($b->codigo == 2) {
                    $pdf->Cell(20, 6, 'IVA', 1, 0, 'C', 0);
                } else if ($b->codigo == 1) {
                    $pdf->Cell(20, 6, 'RENTA', 1, 0, 'C', 0);
                } else {
                    $pdf->Cell(20, 6, $b->codigo, 1, 0, "C", 0);
                }
                $pdf->Cell(20, 6, $b->porcentajeRetener . "%", 1, 0, 'R', 0);
                $pdf->Cell(25, 6, $b->valorRetenido, 1, 0, 'R', 0);
                $pdf->Ln();
            }
            
        }
        foreach ($document->impuestos->impuesto as $a => $b) {
            if($b->codigo == 1 && $b->baseImponible>0){
                if ($b->codDocSustento = '01') {
                    $pdf->Cell(25, 6, 'FACTURA', 1, 0, "C", 0);
                } else {
                    $pdf->Cell(25, 6, $b->codDocSustento, 1, 0, "C", 0);
                }
                $pdf->Cell(30, 6, $serie1."-".$serie2."-".$documento, 1, 0, 'R', 0);
                $pdf->Cell(25, 6, $b->fechaEmisionDocSustento, 1, 0, 'C', 0);
                $pdf->Cell(20, 6, date("Y"), 1, 0, 'C', 0);
                $pdf->Cell(25, 6, $b->baseImponible, 1, 0, 'R', 0);
                if ($b->codigo == 2) {
                    $pdf->Cell(20, 6, 'IVA', 1, 0, 'C', 0);
                } else if ($b->codigo == 1) {
                    $pdf->Cell(20, 6, 'RENTA', 1, 0, 'C', 0);
                } else {
                    $pdf->Cell(20, 6, $b->codigo, 1, 0, "C", 0);
                }
                $pdf->Cell(20, 6, $b->porcentajeRetener . "%", 1, 0, 'R', 0);
                $pdf->Cell(25, 6, $b->valorRetenido, 1, 0, 'R', 0);
                $pdf->Ln();
            }
            
            $total += $b->valorRetenido;
        }
        
        $pdf->Ln(2);
        // $pdf->SetFont('Helvetica', 'B', 8);
        // $pdf->Cell(125, 6, utf8_decode('Información Adicional'), 1, 0, 'L', 0);
        // $pdf->Cell(40, 6, 'Total', 'LTB', 0, 'R', 0);
        // $pdf->Cell(25, 6, number_format(floatval($total),2), 'TBR', 1, 'R', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(125, 6, utf8_decode('Correo: ' . $correo), 'LR', 2, 'L', 0);
        // $pdf->Cell(125, 6, utf8_decode('Observaciones: ' . $infoAdicional), 'LR', 2, 'L', 0);
        // $pdf->Cell(125, 6, utf8_decode('Retencion aplicada a la factura No.: ' . $b->codDocSustento), 'LBR', 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(125, 6, "", 0, 0, 'L', 0);
        $pdf->Cell(40, 6, 'Total', 1, 0, 'C', 0);
        $pdf->Cell(25, 6, number_format($total, 2,".",","), 1, 1, 'R', 0);
        $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(125, 6, utf8_decode('Correo: ' . $factura_compra->email), 'LR', 2, 'L', 0);
        // $pdf->Cell(125, 6, utf8_decode('Observaciones: ' .$infoAdicional ), 'LR', 2, 'L', 0);
        $pdf->Cell(125, 6, "", 0, 2, 'L', 0);

        $pdf->Output(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/retencioncompra/' . $claveAcceso . '.pdf', 'F');
        try{
            $email = new sendEmail();
            $email->enviarCorreo('retencion_compra', $document->infoCompRetencion->razonSocialSujetoRetenido, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
            //dd("si entra al email de autorizar");
        }catch(Exception $e){
             dd("Error auto email:"+$e->getMessage());
        }
        
        //$pdf->Output("ejemplo.pdf", "I");
    }
    public function guiaRemisionPDF($document, $claveAcceso,$id_empresa,$imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa) {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        //$pdf->Cell(40, 10, 'Hello World!');
        if ($document->infoGuiaRemision->obligadoContabilidad == 'SI') {

            $contabilidad = "Obligado a llevar contabilidad : SI";
        } else {
            $contabilidad = "Obligado a llevar contabilidad : NO";
        }

        $pdf->SetXY(10, 0);
        if (file_exists(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen)) {
            $pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 20, 8, 75, 30);
        }
        $pdf->SetXY(110, 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(100, 10, "RUC: " . $document->infoTributaria->ruc, 0, 'J', true);
        $pdf->SetXY(110, 15);
        $pdf->MultiCell(100, 10, "Guia Remision Nro: " . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(110, 20);
        $pdf->MultiCell(100, 10, 'Nro Autorizacion: ', 0);
        $pdf->SetXY(110, 25);
        $pdf->MultiCell(100, 10, $claveAcceso, 0);
        $pdf->SetXY(110, 30);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->MultiCell(100, 10, 'Ambiente: ' . $ambiente, 0);
        $pdf->SetXY(110, 35);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->MultiCell(100, 10, 'Emision: ' . $emision, 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 20);
        $pdf->MultiCell(100, 10, $document->infoTributaria->razonSocial, 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, 25);
        $pdf->MultiCell(100, 10, $document->infoTributaria->dirMatriz, 0);
        $pdf->SetXY(10, 30);
        $pdf->MultiCell(100, 10, $contabilidad, 0);
        //Codigo de barras

        $pdf->SetXY(110, 45);
        $this->generarCodigoBarras($claveAcceso,$id_empresa);
        $pdf->image(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/codigosbarras/codigo_'.$claveAcceso.'.png', null, null, 88, 11);
        $pdf->SetXY(110, 63);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(100, 10, $claveAcceso, 0, 0, "C", true);

        //informacion del cliente
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->SetXY(10, 35);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(100, 10, "INFORMACION DEL TRASPORTISTA", 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, 40);
        $pdf->MultiCell(100, 10, "RUC/CI: " . $document->infoGuiaRemision->rucTransportista, 0);
        $pdf->SetXY(10, 45);
        $pdf->MultiCell(100, 10, "Razon Social/Nombre: " . $document->infoGuiaRemision->razonSocialTransportista, 0);
        $pdf->SetXY(10, 50);
        $pdf->MultiCell(100, 10, "Direccion: " . $document->infoGuiaRemision->dirEstablecimiento, 0);
        $pdf->SetXY(10, 55);
        $pdf->MultiCell(100, 10, "Placa: " . $document->infoGuiaRemision->placa, 0);



        //Fin Encabezado

        $pdf->SetXY(10, 75);
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 255, 255);

        $pdf->Cell(50, 10, "Punto de Partida", 1, 0, "C", true);
        $pdf->Cell(50, 10, "Fecha Inicio", 1, 0, "C", true);
        $pdf->Cell(50, 10, "Fecha Fin", 1, 0, "C", true);

        $pdf->SetXY(10, 85);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $codigo = rand(1000, 9999);

        $pdf->Cell(50, 10, $document->infoGuiaRemision->dirPartida, 1, 0, "L");
        $pdf->Cell(50, 10, $document->infoGuiaRemision->fechaIniTransporte, 1, 0, "L");
        $pdf->Cell(50, 10, $document->infoGuiaRemision->fechaFinTransporte, 1, 0, "L");



        $pdf->SetXY(10, 100);
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 255, 255);
        $pdf->SetFont('Arial', 'B', 6);

        $pdf->Cell(30, 10, "NIT/CI Destinatario", 1, 0, "C", true);
        $pdf->Cell(40, 10, "Destinatario", 1, 0, "C", true);
        $pdf->Cell(50, 10, "Direccion", 1, 0, "C", true);
        $pdf->Cell(30, 10, "Nro Sustento", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Motivo", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Fecha Emision", 1, 0, "C", true);

        $pdf->SetFont('Arial', '', 6);
        $pdf->SetXY(10, 110);
        $ejeX = 110;
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        foreach ($document->destinatarios->destinatario as $a => $b) {
            $pdf->Cell(30, 10, $b->identificacionDestinatario, 1);
            $pdf->Cell(40, 10, $b->razonSocialDestinatario, 1);
            $pdf->Cell(50, 10, $b->dirDestinatario, 1);
            $pdf->Cell(30, 10, $b->numDocSustento, 1);
            $pdf->Cell(20, 10, $b->motivoTraslado, 1);
            $pdf->Cell(20, 10, $b->fechaEmisionDocSustento, 1);
            $ejeX = $ejeX + 10;
            $pdf->SetX($ejeX);
        }
        //detalle de la factura
        $ejeX = $ejeX + 10;
        $pdf->SetXY(10, $ejeX);
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 255, 255);
        $pdf->Cell(25, 10, "Codigo", 1, 0, "C", true);
        $pdf->Cell(50, 10, "Descripcion", 1, 0, "C", true);
        $pdf->Cell(25, 10, "Cantidad", 1, 0, "C", true);


        $ejeX = $ejeX + 10;
        $pdf->SetXY(10, $ejeX);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        foreach ($document->destinatarios->destinatario as $a => $b) {
            foreach ($b->detalles->detalle as $c => $d) {
                $pdf->Cell(25, 10, $d->codigoInterno, 1, 0, "C", true);
                $pdf->Cell(50, 10, $d->descripcion, 1, 0, "C", true);
                $pdf->Cell(25, 10, $d->cantidad, 1, 0, "C", true);
                $ejeX = $ejeX + 10;
                $pdf->SetXY(10, $ejeX);
            }
        }

        $infoAdicional = "";
        $correo = "";
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                }
            }
        }
        $pdf->SetXY(10, $ejeX + 30);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(100, 10, "Informacion Adicional", 0);
        $pdf->SetXY(10, $ejeX + 40);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(100, 5, "" . $infoAdicional . "", 0);

        

        // Pie de pagina
        $pdf->Output(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/guia/' . $claveAcceso . '.pdf', 'F');
        try{
            $email = new sendEmail();
            $email->enviarCorreo('guia_remision_venta', $document->infoCompRetencion->razonSocialTransportista, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
        }catch(Exception $e){
             dd("Error auto email:"+$e->getMessage());
        }
    }
    //Solo envios de compra
    public function Facturacompra($rq){
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        //$pdf->Cell(20);
        if(isset($rq->empresa["logo"])){
            if (file_exists(constant("DATA_EMPRESA").$rq->empresa["id_empresa"].'/imagen/'.$rq->empresa["logo"])) {
                $pdf->Image(constant("DATA_EMPRESA").$rq->empresa["id_empresa"].'/imagen/'.$rq->empresa["logo"], 20, 10, 80, 30);
            }
        }

        //variables de empresa que emite
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(11, 41);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(96, 6, utf8_decode($rq->proveedor["nombre"]), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, utf8_decode('Dirección Matriz: ' . $rq->proveedor["direccion"]), 0, 2, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Email: ' . $rq->proveedor["email"]), 0, 2, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Contribuyente Especial.'), 0, 2, 'L', 0);
        if ($rq->empresa["obligado_contabilidad"] == 1) {
            $contabilidad = "SI";
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode('OBLIGADO A LLEVAR CONTABILIDAD: ' . $contabilidad), 0, 2, 'L', 0);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 5, utf8_decode('R.U.C: ') . $rq->proveedor["identificacion"], 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(88, 5, utf8_decode('FACTURA '), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode('No. ') . $rq->factura["nfactura"], 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode($rq->factura["autorizacion"]), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: ') . $rq->factura["fecha_validez"], 0, 2, 'L', 0);
        if ($rq->empresa["ambiente"] == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(88, 5, utf8_decode('AMBIENTE: ' . $ambiente), 0, 2, 'L', 0);
        if ($rq->empresa["tipo_emision"] == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(88, 5, utf8_decode('EMISIÓN: ' . $emision), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'L', 0);
        $pdf->Cell(88, 5, $rq->factura["autorizacion"], 0, 2, 'L', 0);
        $pdf->SetXY(111, 61);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($rq->factura["autorizacion"], $rq->empresa["id_empresa"]);
        $pdf->image(constant("DATA_EMPRESA") . $rq->empresa["id_empresa"] . '/comprobantes/factura/codigosbarras/codigo_' . $rq->factura["autorizacion"] . '.png', null, null, 88, 7);
        
        $cli_telefono = '';
        $cli_guias = '';
        $emp_vendedor = '';
        $infoAdicional = "";
        $correo = "";
        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->RoundedRect(10, 72, 190, 27, 2, '1234', 'D');
        $pdf->Cell(110, 5, utf8_decode('Razón Social / Nombres y Apellidos: ' . $rq->empresa["razon_social"]), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('RUC / CI.: ' . $rq->empresa["ruc_empresa"]), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Correo: ' . $rq->empresa["email_empresa"]), 0, 1, 'L', 0); //ojo
        $pdf->Cell(110, 5, utf8_decode('Fecha de Emisión: ' . $rq->factura["fecha_emision"]), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Usuario: ' . $rq->empresa["nombre_empresa"]), 0, 1, 'L', 0); //ojo
        $pdf->Cell(110, 5, utf8_decode('Dirección: ' . $rq->empresa["direccion_empresa"]), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Teléfonos: ' . $rq->empresa["telefono"]), 0, 0, 'L', 0); //ojo

        //tabla de productos
        $pdf->SetXY(10, 101);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->Cell(25, 8, utf8_decode('Código Principal'), 1, 0, 'C', 0);
        $pdf->Cell(15, 8, utf8_decode('Cantidad'), 1, 0, 'C', 0);
        $pdf->Cell(75, 8, utf8_decode('Descripción'), 1, 0, 'C', 0);
        $pdf->Cell(25, 8, utf8_decode('Precio Unitario'), 1, 0, 'C', 0);
        $pdf->Cell(25, 8, utf8_decode('Descuento'), 1, 0, 'C', 0);
        $pdf->Cell(25, 8, utf8_decode('Precio Total'), 1, 0, 'C', 0);
        $pdf->Ln();

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        for($i = 0; $i< count($rq->productos); $i++){
            $pdf->Cell(25, 6, $rq->productos[$i]["cod_principal"] , 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $rq->productos[$i]["cantidad"], 1, 0, 'R', 0);
            $pdf->Cell(75, 6, $rq->productos[$i]["nombre"], 1, 0, 'L', 0);
            $pdf->Cell(25, 6, number_format(floatval($rq->productos[$i]["precio"]), 2), 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $rq->productos[$i]["descuento"], 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $rq->productos[$i]["subtotal"], 1, 0, 'R', 0);
            $pdf->Ln();
        }
        $forma_pagos_id="";
        if($rq->pagos["estado"]){
            for ($a = 0; $a < count($rq->pagos["datos"]); $a++) {
                $forma_pagos_id .=$rq->pagos["datos"][$a]["metodo_pago"].",";
            }
        }
        // $ejeX = 65;
        // $ejeX = $ejeX + 20;
        // $pdf->SetXY(10, $ejeX);
        $formaPago = 'Sin utilizacion del sistema financiero';
        $total = 0;
        $plazo = 0;
        $unidadTiempo = 0;
        if(isset($rq->creditos["periodo"])){
            $total = $rq->total_pagado;
            $plazo = $rq->creditos["plazos"];
            $unidadTiempo = $rq->creditos["tiempo"];
        }
        $pdf->Ln();
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->subtotal12, 1, 1, 'R', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, 'PAGOS:', 'LTR', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(50, 6, 'SUBTOTAL 0%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->subtotal0, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('FORMA DE PAGO SRI: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($formaPago), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->no_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('TOTAL: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($total), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->exento, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('PLAZO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($plazo), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->subtotal, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('UNID. DE TIEMPO: '), 'L', 0, 'L', 0);
        $pdf->Cell(75, 6, utf8_decode($unidadTiempo), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'DESCUENTO', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->descuento, 1, 1, 'R', 0);
        $pdf->Cell(40, 6, utf8_decode('FORMA DE PAGO: '), 'L', 0, 'L', 0);
        if($rq->creditos["estado"]){
            $pdf->Cell(75, 6, "CREDITO", 'R', 0, 'L', 0);
        }else{
            $pdf->Cell(75, 6, utf8_decode($forma_pagos_id), 'R', 0, 'L', 0);
        }
        
        $pdf->Cell(50, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, '0.00', 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(50, 6, '12%', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->valor12, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, '0.00', 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'PROPINA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, '0.00', 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(50, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->total_pagado, 1, 1, 'R', 0);
        $pdf->Ln();
        if ($rq->retencion_estado) {
            if ($rq->valorretenciones[0]["iva"] != null || $rq->valorretenciones[0]["renta"] != null) {
                $pdf->Cell(25, 6, "RETENCIONES:", 0, 1, 'R', 0);
                $pdf->Cell(25, 6, $rq->factura["clave_acceso"], 0, 1, 'R', 0);
            }
        }
        
        $pdf->Output(constant("DATA_EMPRESA") . $rq->empresa["id_empresa"] . '/comprobantes/facturacompra/' . $rq->factura["autorizacion"] . '.pdf', 'F');
        return 'bien';
    }
    public function notaCreditocompra($rq){
        $pdf = new PDF_MC_Table('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists(constant("DATA_EMPRESA").$rq->empresa["id_empresa"].'/imagen/'.$rq->empresa["logo"])) {
            $pdf->Image(constant("DATA_EMPRESA").$rq->empresa["id_empresa"].'/imagen/'.$rq->empresa["logo"], 20, 10, 80, 30);
        }
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($rq->empresa["razon_social"]), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(96, 6, utf8_decode($rq->empresa["razon_social"]), 0, 2, 'L', 0);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($rq->empresa["direccion_empresa"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($rq->empresa["direccion_empresa"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        if ($rq->empresa["obligado_contabilidad"] == 1) {
            $contabilidad = "SI"; 
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($rq->empresa["ambiente"] == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($rq->empresa["tipo_emision"] == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 5, $rq->factura["autorizacion"], 0, 2, 'L', 0);
        $pdf->SetXY(111, 55);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($rq->factura["autorizacion"], $rq->empresa["id_empresa"]);
        $pdf->image(constant("DATA_EMPRESA") . $rq->empresa["id_empresa"] . '/comprobantes/factura/codigosbarras/codigo_' . $rq->factura["autorizacion"] . '.png', null, null, 88, 7);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $rq->empresa["ruc_empresa"], 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('NOTA DE CRÉDITO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $rq->factura["documento"], 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($rq->factura["autorizacion"]), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        $cli_correo = '';
        $cli_telefono = '';
        $infoAdicional = "";
        $correo = "";

        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->RoundedRect(10, 72, 190, 29, 2, '1234', 'D');
        $pdf->Cell(51, 5, utf8_decode('Razón Social / Nombres y Apellidos: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($rq->proveedorc["nombre_proveedor"]), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(21, 5, utf8_decode('Identificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(110, 5, utf8_decode($rq->proveedorc["identif_proveedor"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(11, 5, utf8_decode('Fecha: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, utf8_decode($rq->factura["fecha"]), 0, 1, 'L', 0);
        //línea
        $pdf->Line(20, 85, 190, 85);
        //datos del comprobante devuelto
        $tipo_comprobante = 'FACTURA';
        $pdf->SetXY(10, 86);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Comprobante que se modifica: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(40, 5, utf8_decode($tipo_comprobante), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Fecha de Emisión (Comprobante a modificar): '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($rq->factura["fecha_doc"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Razón de Modificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($rq->factura["motivo"]), 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 103);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(15, 8, utf8_decode('Código'), 1, 'C', 1);
        $pdf->SetXY(25, 103);
        $pdf->MultiCell(15, 4, utf8_decode('Código Auxiliar'), 1, 'C', 1);
        $pdf->SetXY(40, 103);
        $pdf->MultiCell(15, 8, utf8_decode('Cantidad'), 1, 'C', 1);
        $pdf->SetXY(55, 103);
        $pdf->MultiCell(86, 8, utf8_decode('Descripción'), 1, 'C', 1);
        // $pdf->SetXY(90, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(107, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(124, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(141, 103);
        $pdf->MultiCell(19, 4, utf8_decode('Precio Unitario'), 1, 'C', 1);
        $pdf->SetXY(160, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Descuento'), 1, 'C', 1);
        $pdf->SetXY(180, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Precio Total'), 1, 'C', 1);
        $pdf->SetWidths(array(15,15,15,86,19,20,20));
        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $subt = 0.0;
        for($i = 0; $i< count($rq->productos); $i++){
            // $pdf->Cell(15, 6, $rq->productos[$i]["cod_principal"], 1, 0, 'C', 0);
            // $pdf->Cell(15, 6, $rq->productos[$i]["id_producto"], 1, 0, 'R', 0);
            // $pdf->Cell(15, 6, $rq->productos[$i]["cantidad"], 1, 0, 'L', 0);
            // $pdf->Cell(35, 6, $rq->productos[$i]["nombre"], 1, 0, 'R', 0);
            // $pdf->Cell(17, 6, '', 1, 0, 'C', 0);
            // $pdf->Cell(17, 6, '', 1, 0, 'R', 0);
            // $pdf->Cell(17, 6, '', 1, 0, 'L', 0);
            // $pdf->Cell(19, 6, number_format(floatval($rq->productos[$i]["precio"]), 2), 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $rq->productos[$i]["descuento"], 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $rq->productos[$i]["subtotal"], 1, 0, 'R', 0);
            // $pdf->Ln();
            $data = array(
                $rq->productos[$i]["cod_principal"]."Centrado",
                $rq->productos[$i]["id_producto"]."Derecha",
                $rq->productos[$i]["cantidad"],
                $rq->productos[$i]["nombre"],

                number_format(floatval($rq->productos[$i]["precio"]), 2)."Derecha",
                $rq->productos[$i]["descuento"]."Derecha",
                $rq->productos[$i]["subtotal"]."Derecha",
            );
            $pdf->RowCtas($data, 10);
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, utf8_decode('Información Adicional:'), 'LTR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->subtotal12, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Dirección: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($rq->empresa["direccion_empresa"]), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL IVA 0%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->subtotal0, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Teléfono: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($rq->empresa["telefono"]), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->no_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Email: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($rq->empresa["email_empresa"]), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->exento, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->subtotal, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 0, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IVA 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->valor12, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 0, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->total, 1, 1, 'R', 0);
        $pdf->Output(constant("DATA_EMPRESA") . $rq->empresa["id_empresa"] . '/comprobantes/notacreditocompra/' . $rq->factura["autorizacion"] . '.pdf', 'F');
        return "bien";
    }
    public function notaCreditocompraEjemplo($empresa,$proveedor,$ntc,$detalle){

        $pdf = new PDF_MC_Table('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists(constant("DATA_EMPRESA").$empresa->id_empresa.'/imagen/'.$empresa->logo)) {
            $pdf->Image(constant("DATA_EMPRESA").$empresa->id_empresa.'/imagen/'.$empresa->logo, 20, 10, 80, 30);
        }
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($empresa->razon_social), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(96, 6, utf8_decode($empresa->razon_social), 0, 2, 'L', 0);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($empresa->direccion_empresa), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($empresa->direccion_establecimiento), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        if ($empresa->obligado_contabilidad == 1) {
            $contabilidad = "SI"; 
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($empresa->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($empresa->tipo_emision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 5, $ntc->clave_acceso, 0, 2, 'L', 0);
        $pdf->SetXY(111, 55);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($ntc->clave_acceso, $empresa->id_empresa);
        $pdf->image(constant("DATA_EMPRESA") . $empresa->id_empresa . '/comprobantes/factura/codigosbarras/codigo_' . $ntc->clave_acceso . '.png', null, null, 88, 7);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $empresa->ruc_empresa, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('NOTA DE CRÉDITO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $ntc->nro_nota_credito, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($ntc->clave_acceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        $cli_correo = '';
        $cli_telefono = '';
        $infoAdicional = "";
        $correo = "";

        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->RoundedRect(10, 72, 190, 29, 2, '1234', 'D');
        $pdf->Cell(51, 5, utf8_decode('Razón Social / Nombres y Apellidos: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($proveedor->nombre_proveedor), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(21, 5, utf8_decode('Identificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(110, 5, utf8_decode($proveedor->identif_proveedor), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(11, 5, utf8_decode('Fecha: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, $ntc->fecha_emision, 0, 1, 'L', 0);
        //línea
        $pdf->Line(20, 85, 190, 85);
        //datos del comprobante devuelto
        $tipo_comprobante = 'FACTURA ' . utf8_decode('No. ') . $ntc->autorizacionfactura;
        $pdf->SetXY(10, 86);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Comprobante que se modifica: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(40, 5, utf8_decode($tipo_comprobante), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Fecha de Emisión (Comprobante a modificar): '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($ntc->fechaAutorizacion), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Razón de Modificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($ntc->motivo), 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 103);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(15, 8, utf8_decode('Código'), 1, 'C', 1);
        $pdf->SetXY(25, 103);
        $pdf->MultiCell(15, 4, utf8_decode('Código Auxiliar'), 1, 'C', 1);
        $pdf->SetXY(40, 103);
        $pdf->MultiCell(15, 8, utf8_decode('Cantidad'), 1, 'C', 1);
        $pdf->SetXY(55, 103);
        $pdf->MultiCell(86, 8, utf8_decode('Descripción'), 1, 'C', 1);
        // $pdf->SetXY(90, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(107, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        // $pdf->SetXY(124, 103);
        // $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(141, 103);
        $pdf->MultiCell(19, 4, utf8_decode('Precio Unitario'), 1, 'C', 1);
        $pdf->SetXY(160, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Descuento'), 1, 'C', 1);
        $pdf->SetXY(180, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Precio Total'), 1, 'C', 1);
        $pdf->SetWidths(array(15,15,15,86,19,20,20));
        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $subt = 0.0;
        for($i = 0; $i< count($detalle); $i++){
            // $pdf->Cell(15, 6, $rq->productos[$i]["cod_principal"], 1, 0, 'C', 0);
            // $pdf->Cell(15, 6, $rq->productos[$i]["id_producto"], 1, 0, 'R', 0);
            // $pdf->Cell(15, 6, $rq->productos[$i]["cantidad"], 1, 0, 'L', 0);
            // $pdf->Cell(35, 6, $rq->productos[$i]["nombre"], 1, 0, 'R', 0);
            // $pdf->Cell(17, 6, '', 1, 0, 'C', 0);
            // $pdf->Cell(17, 6, '', 1, 0, 'R', 0);
            // $pdf->Cell(17, 6, '', 1, 0, 'L', 0);
            // $pdf->Cell(19, 6, number_format(floatval($rq->productos[$i]["precio"]), 2), 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $rq->productos[$i]["descuento"], 1, 0, 'R', 0);
            // $pdf->Cell(20, 6, $rq->productos[$i]["subtotal"], 1, 0, 'R', 0);
            // $pdf->Ln();
            $data = array(
                $detalle[$i]->cod_principal."Centrado",
                $detalle[$i]->id_producto."Derecha",
                $detalle[$i]->cantidad,
                utf8_decode($detalle[$i]->nombre),
                number_format($detalle[$i]->precio, 2,".",",")."Derecha",
                number_format($detalle[$i]->descuento, 2,".",",")."Derecha",
                number_format($detalle[$i]->total, 2,".",",")."Derecha",
            );
            $pdf->RowCtas($data, 10);
        }
        $pdf->Ln();
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, utf8_decode('Información Adicional:'), 'LTR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($ntc->subtotal_12,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Dirección: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($empresa->direccion_empresa), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL IVA 0%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($ntc->subtotal_0,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Teléfono: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($empresa->telefono), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($ntc->subtotal_no_obj_iva,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Email: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($empresa->email_empresa), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format(0,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($ntc->subtotal_sin_impuesto,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($ntc->valor_ice,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IVA 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($ntc->iva_12,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format(0,2,".",","), 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, number_format($ntc->valor_total,2,".",","), 1, 1, 'R', 0);
        $pdf->Output(constant("DATA_EMPRESA") . $empresa->id_empresa . '/comprobantes/notacreditocompra/' . $ntc->clave_acceso . '.pdf', 'F');
        return "bien";
    }
    public function notaDebitocompra($rq){
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists(constant("DATA_EMPRESA").$rq->empresa["id_empresa"].'/imagen/'.$rq->empresa["logo"])) {
            $pdf->Image(constant("DATA_EMPRESA").$rq->empresa["id_empresa"].'/imagen/'.$rq->empresa["logo"], 20, 10, 80, 30);
        }
        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($rq->empresa["razon_social"]), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(96, 6, utf8_decode($rq->empresa["razon_social"]), 0, 2, 'L', 0);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($rq->empresa["direccion_empresa"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($rq->empresa["direccion_empresa"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        if ($rq->empresa["obligado_contabilidad"] == 1) {
            $contabilidad = "SI"; 
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($rq->empresa["ambiente"] == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($rq->empresa["tipo_emision"] == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 5, $rq->factura["autorizacion"], 0, 2, 'L', 0);
        $pdf->SetXY(111, 55);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($rq->factura["autorizacion"], $rq->empresa["id_empresa"]);
        $pdf->image(constant("DATA_EMPRESA") . $rq->empresa["id_empresa"] . '/comprobantes/factura/codigosbarras/codigo_' . $rq->factura["autorizacion"] . '.png', null, null, 88, 7);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $rq->empresa["ruc_empresa"], 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('NOTA DE CRÉDITO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $rq->factura["documento"], 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($rq->factura["autorizacion"]), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        $cli_correo = '';
        $cli_telefono = '';
        $infoAdicional = "";
        $correo = "";

        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->RoundedRect(10, 72, 190, 29, 2, '1234', 'D');
        $pdf->Cell(51, 5, utf8_decode('Razón Social / Nombres y Apellidos: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($rq->proveedorc["nombre_proveedor"]), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(21, 5, utf8_decode('Identificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(110, 5, utf8_decode($rq->proveedorc["identif_proveedor"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(11, 5, utf8_decode('Fecha: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, utf8_decode($rq->factura["fecha"]), 0, 1, 'L', 0);
        //línea
        $pdf->Line(20, 85, 190, 85);
        //datos del comprobante devuelto
        $tipo_comprobante = 'FACTURA';
        $pdf->SetXY(10, 86);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Comprobante que se modifica: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(40, 5, utf8_decode($tipo_comprobante), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Fecha de Emisión (Comprobante a modificar): '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($rq->factura["fecha_doc"]), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Razón de Modificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($rq->factura["motivo"]), 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 103);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(15, 8, utf8_decode('Código'), 1, 'C', 1);
        $pdf->SetXY(25, 103);
        $pdf->MultiCell(15, 4, utf8_decode('Código Auxiliar'), 1, 'C', 1);
        $pdf->SetXY(40, 103);
        $pdf->MultiCell(15, 8, utf8_decode('Cantidad'), 1, 'C', 1);
        $pdf->SetXY(55, 103);
        $pdf->MultiCell(35, 8, utf8_decode('Descripción'), 1, 'C', 1);
        $pdf->SetXY(90, 103);
        $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(107, 103);
        $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(124, 103);
        $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(141, 103);
        $pdf->MultiCell(19, 4, utf8_decode('Precio Unitario'), 1, 'C', 1);
        $pdf->SetXY(160, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Descuento'), 1, 'C', 1);
        $pdf->SetXY(180, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Precio Total'), 1, 'C', 1);

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $subt = 0.0;
        for($i = 0; $i< count($rq->productos); $i++){
            $pdf->Cell(15, 6, $rq->productos[$i]["cod_principal"], 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $rq->productos[$i]["id_producto"], 1, 0, 'R', 0);
            $pdf->Cell(15, 6, $rq->productos[$i]["cantidad"], 1, 0, 'L', 0);
            $pdf->Cell(35, 6, $rq->productos[$i]["nombre"], 1, 0, 'R', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'R', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'L', 0);
            $pdf->Cell(19, 6, number_format(floatval($rq->productos[$i]["precio"]), 2), 1, 0, 'R', 0);
            $pdf->Cell(20, 6, $rq->productos[$i]["descuento"], 1, 0, 'R', 0);
            $pdf->Cell(20, 6, $rq->productos[$i]["subtotal"], 1, 0, 'R', 0);
            $pdf->Ln();
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, utf8_decode('Información Adicional:'), 'LTR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->subtotal12, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Dirección: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($rq->empresa["direccion_empresa"]), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL IVA 0%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->subtotal0, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Teléfono: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($rq->empresa["telefono"]), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $rq->no_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Email: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($rq->empresa["email_empresa"]), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->exento, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->subtotal, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 0, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IVA 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->valor12, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, 0, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $rq->total, 1, 1, 'R', 0);
        $pdf->Output(constant("DATA_EMPRESA") . $rq->empresa["id_empresa"] . '/comprobantes/notadebitocompra/' . $rq->factura["autorizacion"] . '.pdf', 'F');
        return "bien";
    }
    //fin envio compra
    public function generarCodigoBarras($claveAcceso, $id_empresa) {
        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);
        $code = new BCGcode128();
        $code->setScale(4);
        $code->setThickness(30);
        $code->setForegroundColor($colorFront);
        $code->setBackgroundColor($colorBack);
        $code->parse($claveAcceso);
        $drawing = new BCGDrawing(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/codigosbarras/codigo'.$claveAcceso.'.png', $colorBack);
        $drawing->setBarcode($code);
        $drawing->draw();
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
        $this->redim(constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/codigosbarras/codigo'.$claveAcceso.'.png', constant("DATA_EMPRESA").$id_empresa.'/comprobantes/factura/codigosbarras/codigo_'.$claveAcceso.'.png', 1000, 200);
    }
    public function redim($ruta1, $ruta2, $ancho, $alto) {
        # se obtene la dimension y tipo de imagen 
        $datos = getimagesize($ruta1);

        $ancho_orig = $datos[0]; # Anchura de la imagen original 
        $alto_orig = $datos[1];    # Altura de la imagen original 
        $tipo = $datos[2];

        if ($tipo == 1) { # GIF 
            if (function_exists("imagecreatefromgif"))
                $img = imagecreatefromgif($ruta1);
            else
                return false;
        }
        else if ($tipo == 2) { # JPG 
            if (function_exists("imagecreatefromjpeg"))
                $img = imagecreatefromjpeg($ruta1);
            else
                return false;
        }
        else if ($tipo == 3) { # PNG 
            if (function_exists("imagecreatefrompng"))
                $img = imagecreatefrompng($ruta1);
            else
                return false;
        }

        # Se calculan las nuevas dimensiones de la imagen 
        if ($ancho_orig > $alto_orig) {
            $ancho_dest = $ancho;
            $alto_dest = ($ancho_dest / $ancho_orig) * $alto_orig;
        } else {
            $alto_dest = $alto;
            $ancho_dest = ($alto_dest / $alto_orig) * $ancho_orig;
        }

        // imagecreatetruecolor, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        $img2 = @imagecreatetruecolor($ancho_dest, $alto_dest) or $img2 = imagecreate($ancho_dest, $alto_dest);

        // Redimensionar 
        // imagecopyresampled, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        @imagecopyresampled($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig) or imagecopyresized($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig);

        // Crear fichero nuevo, según extensión. 
        if ($tipo == 1) // GIF 
            if (function_exists("imagegif"))
                imagegif($img2, $ruta2);
            else
                return false;

        if ($tipo == 2) // JPG 
            if (function_exists("imagejpeg"))
                imagejpeg($img2, $ruta2);
            else
                return false;

        if ($tipo == 3)  // PNG 
            if (function_exists("imagepng"))
                imagepng($img2, $ruta2);
            else
                return false;

        return true;
    }
    public function liquidacionIndividual(){
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $mes = date('m');
        $anio = date('Y');
        $nombre_empresa = 'STB TECHNOLOGYEC';



        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        //$pdf->Image(constant("DATA_EMPRESA").$id_empresa.'/imagen/'.$imagen, 12, 12, 20,30);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(65, 10);
        $pdf->Cell(100, 10, utf8_decode($nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 8, utf8_decode('REPORTE LIQUIDACIÓN'), 0, 2, 'C', 0);

        $pdf->SetXY(165, 11);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 6, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(10, 6, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(175, 11);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 6, $fecha_actual, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        $num_liquidacion = '004';
        $fec_liquidacion = date('d/m/Y');
        $bodega = 'BODEGA EJEMPLO';
        $pdf->SetY(35);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 6, utf8_decode('PRODUCTOS'), 0, 1, 'L', 1);
        $pdf->Ln();
        $pdf->SetX(30);
        $pdf->Cell(50, 6, utf8_decode('Número de Liquidación:'), 0, 0, 'L', 0);
        $pdf->Cell(50, 6, utf8_decode('Fecha de Liquidación:'), 0, 0, 'L', 0);
        $pdf->Cell(50, 6, utf8_decode('Bodega:'), 0, 1, 'L', 0);
        $pdf->SetX(30);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 6, $num_liquidacion, 0, 0, 'L', 0);
        $pdf->Cell(50, 6, $fec_liquidacion, 0, 0, 'L', 0);
        $pdf->Cell(50, 6, utf8_decode($bodega), 0, 1, 'L', 0);
        $pdf->Ln();


        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, utf8_decode('NOMBRE'), 0, 0, 'C', 1);
        $pdf->Cell(40, 6, utf8_decode('CANTIDAD'), 0, 0, 'C', 1);
        $pdf->Cell(40, 6, utf8_decode('COSTO UNITARIO'), 0, 0, 'C', 1);
        $pdf->Cell(40, 6, utf8_decode('COSTO TOTAL'), 0, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        // foreach ($datos as $detail) {
        //     $pdf->SetX(20);
        //     $pdf->Cell(50, 6, utf8_decode($detail->primer_nombre), 0, 0, 'C', 0);
        //     $pdf->Cell(40, 6, $detail->id_empleado, 0, 0, 'C', 0);
        //     $pdf->Cell(40, 6, '$' .  $detail->id_empleado, 0, 0, 'C', 0);
        //     $pdf->Cell(40, 6, '$' . $detail->id_empleado, 0, 0, 'C', 0);
        //     $pdf->Ln();
        // }


        $cant_tot = 3;
        $sum_tot = 24.00;
        $pdf->Ln();
        $pdf->SetX(65);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 6, utf8_decode('Cantidad Total'), 0, 0, 'C', 1);
        $pdf->Cell(40, 6, utf8_decode('Costo Total'), 0, 1, 'C', 1);
        $pdf->SetX(65);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 6, $cant_tot, 0, 0, 'C', 0);
        $pdf->Cell(40, 6, '$' . $sum_tot, 0, 0, 'C', 0);
        $pdf->Ln();
        $pdf->Ln();

        $fac_id = 49;
        $fac_desc = 'producto 1';
        $total = 1;
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 6, utf8_decode('CÁLCULOS'), 0, 1, 'L', 1);
        $pdf->Ln();
        $pdf->SetX(50);
        $pdf->Cell(30, 6, utf8_decode('ID'), 0, 0, 'C', 1);
        $pdf->Cell(50, 6, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C', 1);
        $pdf->Cell(30, 6, utf8_decode('TOTAL'), 0, 1, 'C', 1);
        $pdf->SetFont('Arial', '', 10);
        // foreach ($datos as $detail) {
        //     $pdf->SetX(50);
        //     $pdf->Cell(30, 6, $fac_id, 0, 0, 'R', 0);
        //     $pdf->Cell(50, 6, utf8_decode($fac_desc), 0, 0, 'L', 0);
        //     $pdf->Cell(30, 6, utf8_decode($total . '00'), 0, 1, 'R', 0);
        // }
        //$pdf->Ln();
        $pdf->SetX(50);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(80, 6, utf8_decode('Total Facturas'), 'T', 0, 'R', 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 6, utf8_decode('$ ' . $total . '.00'), 'T', 1, 'R', 0);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 6, utf8_decode('TOTAL LIQUIDACIÓN'), 0, 1, 'L', 1);
        $pdf->Ln();
        $pdf->SetX(30);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 6, utf8_decode('SALDO SIN LIQUIDAR'), 0, 0, 'C', 0);
        $pdf->Cell(50, 6, utf8_decode('TOTAL PRODUCTOS'), 0, 0, 'C', 0);
        $pdf->Cell(50, 6, utf8_decode('TOTAL LIQUIDACIÓN'), 0, 1, 'C', 0);
        $pdf->SetX(30);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(50, 6, '$ ' . $sum_tot . '.00', 0, 0, 'C', 0);
        $pdf->Cell(50, 6, $cant_tot, 0, 0, 'C', 0);
        $pdf->Cell(50, 6, '$ ' . $sum_tot . '.00', 0, 1, 'C', 0);
        $pdf->Ln();

        $pdf->Output("ejemplo.pdf", "I");
    }
    public function RolPagoGeneral($datos,$fecha_ini,$empresa,$proyecto){
        setlocale(LC_TIME, "spanish");
        $pdf = new PDF_MC_Table('L', 'mm', 'A4');
        $width = $pdf->GetPageWidth();
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $url=constant("DATA_EMPRESA");
        $pdf->SetFont('Arial', 'B', 14);
        
            $pdf->RoundedRect(8, 10, 279, 20, 2, '1234', 'D');
        
        
        //dd($empresa);
        $logo="";
        $logo = constant("DATA_EMPRESA")  . $empresa[0]->id_empresa . '/imagen/' . $empresa[0]->logo;
        if (strlen($empresa[0]->logo)>2 && file_exists($logo)) {
            $pdf->Image($logo, 9, 10, 45, 18);
            
        } else {
            $pdf->SetX(10);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 0, 'C', 0);
            $pdf->SetTextColor(0, 0, 0);
        }
        /*$pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)) / 2), 10);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)), 8, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('ROL PAGO GENERAL')) / 2), 20);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('ROL PAGO GENERAL')), 8, utf8_decode('ROL PAGO GENERAL'), 0, 2, 'C', 0);*/
        $pdf->SetFont('Arial', 'B', 14);
        if($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa))>164){
            $pdf->SetXY(70, 10.5);
            $pdf->MultiCell(164, 4, utf8_decode($datos[0]->nombre_empresa), 0, 'C', 0);
        }else{
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)) / 2), 10);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)), 7, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        }
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('ROL PAGO GENERAL')) / 2)+5, 17);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('ROL PAGO GENERAL')), 8, utf8_decode('ROL PAGO GENERAL'), 0, 2, 'C', 0);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('Proyecto: '.ucfirst($proyecto))) / 2), 23);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('Proyecto: '.ucfirst($proyecto))), 8, utf8_decode('Proyecto: '.ucfirst($proyecto)), 0, 2, 'C', 0);

        $pdf->SetFont('Arial', 'B', 12);
        
        $pdf->SetXY(($width / 6) * 4.5, 12.5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Periodo:'), 0, 3, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 3, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Departamento: '), 0, 3, 'L', 0);
        $pdf->SetXY(($width / 6) * 5, 12.5);
        // $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        if ($fecha_ini != null) {
            $fec_desde = strftime("%B del %G", strtotime($fecha_ini));
        }
        $pdf->Cell($pdf->GetStringWidth($fec_desde), 5, $fec_desde, 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth($pdf->PageNo() . ' de {nb}'), 5, $pdf->PageNo() . ' de {nb}' , 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->dep_nombre)), 5, utf8_decode($datos[0]->dep_nombre), 0, 2, 'L', 0);


        $pdf->Ln(20);
        $sumasueldo=0.0;
        $sumaingreso1=0.0;
        $sumaingreso2=0.0;
        $sumaingreso3=0.0;
        $sumaingreso4=0.0;
        $sumaingreso5=0.0;
        $sumaingreso6=0.0;
        $sumadecimotercero=0.0;
        $sumadecimocuarto=0.0;
        $sumafondoreserva=0.0;
        $sumatotalingreso=0.0;
        $sumadecimal=0.0;
        foreach ($datos as $detail) {
            $sumasueldo+=$detail->sueldo;
            $sumaingreso1+=$detail->ingreso1;
            $sumaingreso2+=$detail->ingreso2;
            $sumaingreso3+=$detail->ingreso3;
            $sumaingreso4+=$detail->ingreso4;
            $sumaingreso5+=$detail->ingreso5;
            $sumaingreso6+=$detail->ingreso6;
            $sumadecimotercero+=$detail->decimo_tercero;
            $sumadecimocuarto+=$detail->decimo_cuarto;
            $sumafondoreserva+=$detail->fondo_reserva;
            $sumatotalingreso+=$detail->total_ingreso;
        }
        $sumatot_ingreso1=number_format($sumaingreso1,2,",",".");
        $sumatot_ingreso2=number_format($sumaingreso2,2,",",".");
        $sumatot_ingreso3=number_format($sumaingreso3,2,",",".");
        $sumatot_ingreso4=number_format($sumaingreso4,2,",",".");
        $sumatot_ingreso5=number_format($sumaingreso5,2,",",".");
        $sumatot_ingreso6=number_format($sumaingreso6,2,",",".");
        $sumadecimotercero;
        $sumadecimocuarto;
        $sumafondoreserva;
        $sumatotalingreso;
        $length_ingreso1=strlen($datos[0]->id_ingreso1)+15;
        $length_ingreso2=strlen($datos[0]->id_ingreso2)+15;
        $length_ingreso3=strlen($datos[0]->id_ingreso3)+15;
        $length_ingreso4=strlen($datos[0]->id_ingreso4)+15;
        $length_ingreso5=strlen($datos[0]->id_ingreso5)+15;
        $length_ingreso6=strlen($datos[0]->id_ingreso6)+15;
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('INGRESOS')) / 2), 35);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('Ingresos')), 8, utf8_decode('INGRESOS'), 0, 2, 'C', 0);

        $pdf->Ln(20);
        //HEADER FOR TABLE
        $pdf->SetY(45);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $header_data = array('CEDULA', 'NOMBRE', 'SUELDO');
        // $header_widths = array(20,25,15);
        $header_widths = array();
        $count_width = 4;

        if($datos[0]->id_ingreso1){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso1)));
            // array_push($header_widths, $length_ingreso1);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso2){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso2)));
            // array_push($header_widths, $length_ingreso2);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso3){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso3)));
            // array_push($header_widths, $length_ingreso3);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso4){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso4)));
            // array_push($header_widths, $length_ingreso4);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso5){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso5)));
            // array_push($header_widths, $length_ingreso5);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso6){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso6)));
            // array_push($header_widths, $length_ingreso6);
            $count_width = $count_width + 1;
        }
        if($sumadecimotercero!=0){
            array_push($header_data, 'DECIMO TERCERO');
            // array_push($header_widths, 25);
            $count_width = $count_width + 1;
        }
        if($sumadecimocuarto!=0){
            array_push($header_data, 'DECIMO CUARTO');
            // array_push($header_widths, 25);
            $count_width = $count_width + 1;
        }
        if($sumafondoreserva!=0){
            array_push($header_data, 'FONDO RESERVA');
            // array_push($header_widths, 25);
            $count_width = $count_width + 1;
        }
        array_push($header_data, 'TOTAL INGRESOS');
        // array_push($header_widths, 25);
        for ($i=0; $i < $count_width; $i++) {
            array_push($header_widths,(($width - 16) / $count_width));
        }
        $pdf->SetX(8);
        $pdf->SetWidths($header_widths);
        $pdf->RowWithoutImageWithBorder($header_data, 8);

        //fill cells
        $tsant = 0;
        $tdebe = 0;
        $thaber = 0;
        $tsactual = 0;
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetX(8);
        foreach ($datos as $detail) {
            $data = array(
                $detail->dni,
                utf8_decode($detail->primer_nombre),
                $detail->sueldo
            );
            if($sumatot_ingreso1!=0){
                array_push($data,$detail->ingreso1);
            }
            if($sumatot_ingreso2!=0){
                array_push($data,$detail->ingreso2);
            }
            if($sumatot_ingreso3!=0){
                array_push($data,$detail->ingreso3);
            }
            if($sumatot_ingreso4!=0){
                array_push($data,$detail->ingreso4);
            }
            if($sumatot_ingreso5!=0){
                array_push($data,$detail->ingreso5);
            }
            
            if($sumatot_ingreso6!=0){
                array_push($data,$detail->ingreso6);
            }/*else{
                array_push($data,);
            }*/
            
            if($sumadecimotercero!=0){
                array_push($data, $detail->decimo_tercero);
            }
            if($sumadecimocuarto!=0){
                array_push($data, $detail->decimo_cuarto);
            }
            if($sumafondoreserva!=0){
                array_push($data, $detail->fondo_reserva);
            }
            array_push($data,$detail->total_ingreso);
            $pdf->RowData($data, 8, 'C', 3);
            //$pdf->RowWithoutImage($data, 8, 'C', 3);
        }

        $sumadecimal=number_format($sumasueldo,2,",", ".");
        $total_data = array('Total', '', $sumadecimal,);
        if($datos[0]->id_ingreso1){
            array_push($total_data, $sumatot_ingreso1);
        }
        if($datos[0]->id_ingreso2){
            array_push($total_data, $sumatot_ingreso2);
        }
        if($datos[0]->id_ingreso3){
            array_push($total_data, $sumatot_ingreso3);
        }
        if($datos[0]->id_ingreso4){
            array_push($total_data, $sumatot_ingreso4);
        }
        if($datos[0]->id_ingreso5){
            array_push($total_data, $sumaingreso5);
        }
        if($datos[0]->id_ingreso6){
            array_push($total_data, $sumaingreso6);
        }
        if($sumadecimotercero!=0){
            array_push($total_data, $sumadecimotercero);
        }
        if($sumadecimocuarto!=0){
            array_push($total_data, $sumadecimocuarto);
        }
        if($sumafondoreserva!=0){
            array_push($total_data, $sumafondoreserva);
        }
        array_push($total_data, $sumatotalingreso);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetX(8);
        $pdf->RowWithoutImageWithBorder($total_data, 8,'R');
        $head_ingresos=$pdf->GetY();
        if($head_ingresos>105){
            $otro_valor=$pdf->GetPageHeight()-$head_ingresos;
            $pdf->SetAutoPageBreak(true,$otro_valor);
        }
        //dd($head_ingresos);
        $sumaiva=0;
        $sumaegreso1=0;
        $sumaegreso2=0;
        $sumaegreso3=0;
        $sumaegreso4=0;
        $sumaegreso5=0;
        $sumaegreso6=0;
        $sumatotalegreso=0;
        $sumatotalrecibir=0;
        $sumatotalegreso1=0;
        $sumatotalegreso2=0;
        $sumatotalegreso3=0;
        $sumatotalegreso4=0;
        $sumatotalegreso5=0;
        $sumatotalegreso6=0;
        $length_egreso1=strlen($datos[0]->id_egreso1)+15;
        $length_egreso2=strlen($datos[0]->id_egreso2)+15;
        $length_egreso3=strlen($datos[0]->id_egreso3)+15;
        $length_egreso4=strlen($datos[0]->id_egreso4)+15;
        $length_egreso5=strlen($datos[0]->id_egreso5)+15;
        $length_egreso6=strlen($datos[0]->id_egreso6)+15;
        foreach($datos as $detail){
            $sumaiva+=$detail->iess;
            $sumaegreso1+=$detail->egreso1;
            $sumaegreso2+=$detail->egreso2;
            $sumaegreso3+=$detail->egreso3;
            $sumaegreso4+=$detail->egreso4;
            $sumaegreso5+=$detail->egreso5;
            $sumaegreso6+=$detail->egreso6;
            $sumatotalegreso+=$detail->total_egreso;
            $sumatotalrecibir+=$detail->valor_recibir;
        }
        $suma_tot_iva=number_format($sumaiva,2,",",".");
        $sumatotalegreso1=number_format($sumaegreso1,2,",",".");
        $sumatotalegreso2=number_format($sumaegreso2,2,",",".");
        $sumatotalegreso3=number_format($sumaegreso3,2,",",".");
        $sumatotalegreso4=number_format($sumaegreso4,2,",",".");
        $sumatotalegreso5=number_format($sumaegreso5,2,",",".");
        $sumatotalegreso6=number_format($sumaegreso6,2,",",".");
        $suma_toto_totalegreso=number_format($sumatotalegreso,2,",",".");
        $sumatotalrecibir;

        $pdf->SetFont('Arial', 'B', 10);

        $pdf->SetY($pdf->GetY()+5);
        $pdf->SetX(($width / 2) - ($pdf->GetStringWidth(utf8_decode('EGRESOS')) / 2));
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('EGRESOS')), 8, utf8_decode('EGRESOS'), 0, 2, 'C', 0);

        $egresos_data = array('CEDULA', 'NOMBRE');
        $egresos_widths = array();

        $pdf->SetX(8);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $count_egresos = 5;

        if($datos[0]->id_egreso1){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso1)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso2){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso2)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso3){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso3)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso4){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso4)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso5){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso5)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso6){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso6)));
            $count_egresos = $count_egresos + 1;
        }
        array_push($egresos_data, 'IESS 9,45%');
        array_push($egresos_data, 'TOTAL EGRESO');
        array_push($egresos_data, 'VALOR A RECIBIR');

        for ($i=0; $i < $count_egresos; $i++) {
            array_push($egresos_widths,(($width - 16) / $count_egresos));
        }

        $pdf->SetWidths($egresos_widths);
        $pdf->RowWithoutImageWithBorder($egresos_data, 8);

        $pdf->SetFont('Arial', '', 7);
        // $pdf->SetY(70);
        $egresos_values = array();
        $pdf->SetX(8);
        
        foreach ($datos as $detail) {
            array_push($egresos_values, $detail->dni);
            array_push($egresos_values, utf8_decode($detail->primer_nombre));
            //$detail->id_egreso1 || $detail->egreso1 ?  array_push($egresos_values, $detail->egreso1)
            if($sumatotalegreso1!=0){
                array_push($egresos_values, $detail->egreso1);
            }
            if($sumatotalegreso2!=0){
                array_push($egresos_values, $detail->egreso2);
            }
            if($sumatotalegreso3!=0){
                array_push($egresos_values, $detail->egreso3);
            }
            if($sumatotalegreso4!=0){
                array_push($egresos_values, $detail->egreso4);
            }
            if($sumatotalegreso5!=0){
                array_push($egresos_values, $detail->egreso5);
            }
            if($sumatotalegreso6!=0){
                array_push($egresos_values, $detail->egreso6);
            }
            array_push($egresos_values, $detail->iess);
            array_push($egresos_values, $detail->total_egreso);
            array_push($egresos_values, $detail->valor_recibir);
            //$pdf->RowWithoutImage($egresos_values, 8, 'C', 3);
            $pdf->RowData($egresos_values, 8, 'C', 3);
            $egresos_values = array();
        }
        $egresos_total = array();
        $egresos_total_widths = array();

        array_push($egresos_total, 'Total');
        array_push($egresos_total, '');
        if($datos[0]->id_egreso1){
            array_push($egresos_total, $sumatotalegreso1);
        }
        if($datos[0]->id_egreso2){
            array_push($egresos_total, $sumatotalegreso2);
        }
        if($datos[0]->id_egreso3){
            array_push($egresos_total, $sumatotalegreso3);
        }
        if($datos[0]->id_egreso4){
            array_push($egresos_total, $sumatotalegreso4);
        }
        if($datos[0]->id_egreso5){
            array_push($egresos_total, $sumatotalegreso5);
        }
        if($datos[0]->id_egreso6){
            array_push($egresos_total, $sumatotalegreso6);
        }
        array_push($egresos_total, $suma_tot_iva);
        array_push($egresos_total, $suma_toto_totalegreso);
        array_push($egresos_total, number_format($sumatotalrecibir,2,",","."));
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->RowWithoutImageWithBorder($egresos_total, 8,'R');

        $departamento=utf8_decode($datos[0]->dep_nombre);
        $head_egresos=$pdf->GetY();
        if($head_egresos<171){
            $otro_valor=$pdf->GetPageHeight()-$head_egresos;
            $pdf->SetAutoPageBreak(true,2);
        }
        $pdf->SetXY(8,$pdf->GetY()+15);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('ELABORADO POR'),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('APROBADO POR'),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('REVISADO POR'),0,1,'C',0);
        $pdf->SetX(8);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nombres.' '.$empresa[0]->apellidos),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nomb_representante),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nombre_contador),0,1,'C',0);
        // $pdf->SetFont('Arial', 'B', 10);
        // $footer_header = array('ELABORADO POR' ,'APROBADO POR', 'REVISADO POR');
        // $footer_header_widths = array($width / 3,$width / 3,$width / 3);
        // $pdf->SetWidths($footer_header_widths);
        // $pdf->RowFooter($footer_header, 0);
        
        // $pdf->SetFont('Arial', '', 9);
        // $footer_values = array($empresa[0]->nombres.' '.$empresa[0]->apellidos ,utf8_decode($empresa[0]->nomb_representante), utf8_decode($empresa[0]->nombre_contador));
        // $footer_values_widths = array($width / 3,$width / 3,$width / 3);
        // $pdf->SetWidths($footer_values_widths);
        // $pdf->RowFooter($footer_values, 0);

        $pdf->Output("Rol_Pago_General_".$fecha_ini.".pdf","D");
    }
    public function RolPagoGeneralIndividual($datos,$fecha_ini,$empresa){
        setlocale(LC_TIME, "spanish");
        $pdf = new PDF_Papeleta('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $sumasueldo=0.0;
        $sumaingreso1=0.0;
        $sumaingreso2=0.0;
        $sumaingreso3=0.0;
        $sumaingreso4=0.0;
        $sumaingreso5=0.0;
        $sumaingreso6=0.0;
        $sumadecimotercero=0.0;
        $sumadecimocuarto=0.0;
        $sumafondoreserva=0.0;
        $sumatotalingreso=0.0;
        $sumadecimal=0.0;
        foreach ($datos as $detail) {
            $sumasueldo+=$detail->sueldo;
            $sumaingreso1+=$detail->ingreso1;
            $sumaingreso2+=$detail->ingreso2;
            $sumaingreso3+=$detail->ingreso3;
            $sumaingreso4+=$detail->ingreso4;
            $sumaingreso5+=$detail->ingreso5;
            $sumaingreso6+=$detail->ingreso6;
            $sumadecimotercero+=$detail->decimo_tercero;
            $sumadecimocuarto+=$detail->decimo_cuarto;
            $sumafondoreserva+=$detail->fondo_reserva;
            $sumatotalingreso+=$detail->total_ingreso;
        }
        $sumatot_ingreso1=number_format($sumaingreso1,2,",",".");
        $sumatot_ingreso2=number_format($sumaingreso2,2,",",".");
        $sumatot_ingreso3=number_format($sumaingreso3,2,",",".");
        $sumatot_ingreso4=number_format($sumaingreso4,2,",",".");
        $sumatot_ingreso5=number_format($sumaingreso5,2,",",".");
        $sumatot_ingreso6=number_format($sumaingreso6,2,",",".");
        $sumatot_decimotercero=number_format($sumadecimotercero,2,",",".");
        $sumatot_decimocuarto=number_format($sumadecimocuarto,2,",",".");
        $sumatot_fondoreserva=number_format($sumafondoreserva,2,",",".");
        $sumatot_ingresos=number_format($sumatotalingreso,2,",",".");
        $length_ingreso1=strlen($datos[0]->id_ingreso1)+8;
        $length_ingreso2=strlen($datos[0]->id_ingreso2)+8;
        $length_ingreso3=strlen($datos[0]->id_ingreso3)+8;
        $length_ingreso4=strlen($datos[0]->id_ingreso4)+8;
        $length_ingreso5=strlen($datos[0]->id_ingreso5)+8;
        $length_ingreso6=strlen($datos[0]->id_ingreso6)+8;
        
        $width = $pdf->GetPageWidth();
        $margin = 4;
        $nombre_documento = 'ROL INDIVIDUAL';
        $url = constant("DATA_EMPRESA");
        $logo=$url.$datos[0]->id_empresa.'/imagen/'.$datos[0]->logo;
        if (strlen($datos[0]->logo)>2 && file_exists($logo)) {
            $pdf->Image($logo, $margin - 1, 5, ($width / 4) - ($margin * 2), 10);
            
        } else {
            $pdf->SetXY(2, 2);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 0, 'C', 0);
            $pdf->SetTextColor(0, 0, 0);
        }
        
        
        $pdf->SetFont('Arial', 'B', 14);
        if($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa))>130){
            $pdf->SetXY(47, 7);
            $pdf->MultiCell(160, 4, utf8_decode($datos[0]->nombre_empresa), 0,  'C', 0);
            $head_empresa=$pdf->GetY();
        }else{
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)) / 2), 7);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)), 4, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        }
        

        $pdf->SetFont('Arial', 'B', 12);
        if($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa))>130){
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($nombre_documento)) / 2), $head_empresa);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($nombre_documento)), 8, utf8_decode($nombre_documento), 0, 2, 'C', 0);
        }else{
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($nombre_documento)) / 2), 10);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($nombre_documento)), 8, utf8_decode($nombre_documento), 0, 2, 'C', 0);
        }
        
        
        $pdf->SetXY($margin + 2, 17);
        //$pdf->SetFont('Arial', 'B', 8);
        /*
        $pdf->Cell(20, 5, utf8_decode('Codigo:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 5, utf8_decode('Nombre:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 5, utf8_decode('Ciudad:'), 0, 2, 'L', 0);
        $pdf->SetXY(($width / 6), 17);
        $pdf->SetFont('Arial', '', 8);
        if($datos[0]->cod_rol_pago<10){
            $pdf->Cell(15, 5, utf8_decode('0000').$datos[0]->cod_rol_pago, 0, 2, 'L', 0);
        }else{
            if($datos[0]->cod_rol_pago<100){
                $pdf->Cell(15, 5, utf8_decode('000').$datos[0]->cod_rol_pago, 0, 2, 'L', 0);
            }else{
                $pdf->Cell(15, 5, utf8_decode('00').$datos[0]->cod_rol_pago, 0, 2, 'L', 0);
            }
        }
         #Revisar esta forma en la que se genera el codigo.
        $pdf->Cell(15, 5, utf8_decode($datos[0]->primer_nombre), 0, 2, 'L', 0);
        $pdf->Cell(70, 5, utf8_decode('Pichincha'), 0, 2, 'L', 0);
        

        $pdf->SetXY(($width / 6) * 4.2, 17);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Periodo:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Dias Trabajados:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Departamento: '), 0, 2, 'L', 0);
        $pdf->SetXY(($width / 6) * 5, 17);
        // $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        if ($fecha_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fecha_ini));
        }
        $pdf->Cell($pdf->GetStringWidth($fec_desde), 5, $fec_desde, 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth($datos[0]->cantidad), 5, $datos[0]->cantidad , 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->dep_nombre)), 5, utf8_decode($datos[0]->dep_nombre), 0, 2, 'L', 0);
        */
        $codigo=0;
        if($datos[0]->cod_rol_pago<10){
            $codigo='0000'.$datos[0]->cod_rol_pago;
        }else{
            if($datos[0]->cod_rol_pago<100){
                $codigo='000'.$datos[0]->cod_rol_pago;
            }else{
                if($datos[0]->cod_rol_pago<1000){
                    $codigo='00'.$datos[0]->cod_rol_pago;
                }else{
                    if($datos[0]->cod_rol_pago<10000){
                        $codigo='0'.$datos[0]->cod_rol_pago;
                    }else{
                        $codigo=$datos[0]->cod_rol_pago;
                    }
                }
                        
            }
        }
        if ($fecha_ini != null) {
            $fec_desde = strftime("%B del %G", strtotime($fecha_ini));
        }
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetWidths(array(($width / 9) - 5 , ($width / 6),($width / 3),($width / 9),($width / 8), ($width / 6)));
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->HeadWithoutImage(array('Codigo:','','','','Periodo:',''), 4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, $h_content);
        $pdf->HeadWithoutImage(array('',$codigo,'','','',$fec_desde), 4);
        $pdf->SetFont('Arial', 'B', 8);
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->HeadWithoutImage(array('Nombre:','','','','Dias Trabajados:',''), 4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, $h_content );
        $pdf->HeadWithoutImage(array('',utf8_decode($datos[0]->primer_nombre),'','','',$datos[0]->cantidad), 4);
        $pdf->SetFont('Arial', 'B', 8);
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->HeadWithoutImage(array('Ciudad:','','','','Departamento:',''), 4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, $h_content);
        $pdf->HeadWithoutImage(array('',utf8_decode('Pichincha'),'','','',utf8_decode($datos[0]->dep_nombre)), 4);
        
        $dep=$pdf->GetY();
        $pdf->RoundedRect($margin, 4, $width - ($margin * 2), $dep + 1, 2, '1234', 'D');

        //------------------------------Ingresos
        $length_ingreso1=strlen($datos[0]->id_ingreso1)+8;
        $length_ingreso2=strlen($datos[0]->id_ingreso2)+8;
        $length_ingreso3=strlen($datos[0]->id_ingreso3)+8;
        $length_ingreso4=strlen($datos[0]->id_ingreso4)+8;
        $length_ingreso5=strlen($datos[0]->id_ingreso5)+8;
        $length_ingreso6=strlen($datos[0]->id_ingreso6)+8;

        $h_content = $pdf->GetY() + 6;
        $uno_y = $pdf->GetY() + 6;
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetY($h_content);
        $pdf->SetX(0);
        
        $pdf->SetWidths(array(($width / 2), ($width / 2)));
        $pdf->RowWithoutImage(array('INGRESO','EGRESO'), 0);
        
        $pdf->SetXY(10, $h_content + 5);
        
        $pdf->SetFont('Arial', '', 8);

        $pdf->SetWidths(array(($width / 4), ($width / 6)));
        $pdf->RowWithoutImage(array(utf8_decode('SUELDO'),""), $margin + 2, 'L');
        
        $pdf->SetXY(10, $h_content + 5);
        $pdf->RowWithoutImage(array("", $datos[0]->sueldo), $margin + 2, 'R');
        

        if($datos[0]->id_ingreso1){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_ingreso1)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso1), $margin + 2, 'R');
        }
        if($datos[0]->id_ingreso2){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_ingreso2)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso2), $margin + 2, 'R');
        }
        if($datos[0]->id_ingreso3){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_ingreso3)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso3), $margin + 2, 'R');
        }
        if($datos[0]->id_ingreso4){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_ingreso4)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso4), $margin + 2, 'R');
        }
        if($datos[0]->id_ingreso5){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_ingreso5)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso5), $margin + 2, 'R');
        }
        if($datos[0]->id_ingreso6){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_ingreso6)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso6), $margin + 2, 'R');
        }
        if($sumadecimotercero!=0){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array('DECIMO TERCERO', ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $sumatot_decimotercero), $margin + 2, 'R');
        }
        if($sumadecimocuarto!=0){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array('DECIMO CUARTO', ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $sumatot_decimocuarto), $margin + 2, 'R');
        }
        if($sumafondoreserva!=0){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array('FONDO RESERVA', ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $sumatot_fondoreserva), $margin + 2, 'R');
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->RowWithoutImageWithBorder(array('TOTAL', $sumatot_ingresos), $margin + 2, 'R', 'T');
        $y_ingresos = $pdf->GetY();
        //--------------------------------Egresos
        //-----------------valores egresos
        $sumaiva=0;
        $sumaegreso1=0;
        $sumaegreso2=0;
        $sumaegreso3=0;
        $sumaegreso4=0;
        $sumaegreso5=0;
        $sumaegreso6=0;
        $sumatotalegreso=0;
        $sumatotalrecibir=0;
        $sumatotalegreso1=0;
        $sumatotalegreso2=0;
        $sumatotalegreso3=0;
        $sumatotalegreso4=0;
        $sumatotalegreso5=0;
        $sumatotalegreso6=0;
        //----------------longitud egresos
        $length_egreso1=strlen($datos[0]->id_egreso1)+8;
        $length_egreso2=strlen($datos[0]->id_egreso2)+8;
        $length_egreso3=strlen($datos[0]->id_egreso3)+8;
        $length_egreso4=strlen($datos[0]->id_egreso4)+8;
        $length_egreso5=strlen($datos[0]->id_egreso5)+8;
        $length_egreso6=strlen($datos[0]->id_egreso6)+8;
        foreach($datos as $detail){
            $sumaiva+=$detail->iess;
            $sumaegreso1+=$detail->egreso1;
            $sumaegreso2+=$detail->egreso2;
            $sumaegreso3+=$detail->egreso3;
            $sumaegreso4+=$detail->egreso4;
            $sumaegreso5+=$detail->egreso5;
            $sumaegreso6+=$detail->egreso6;
            $sumatotalegreso+=$detail->total_egreso;
            $sumatotalrecibir+=$detail->valor_recibir;
        }
        $sumatot_iva=number_format($sumaiva,2,",",".");
        $sumatotalegreso1=number_format($sumaegreso1,2,",",".");
        $sumatotalegreso2=number_format($sumaegreso2,2,",",".");
        $sumatotalegreso3=number_format($sumaegreso3,2,",",".");
        $sumatotalegreso4=number_format($sumaegreso4,2,",",".");
        $sumatotalegreso5=number_format($sumaegreso5,2,",",".");
        $sumatotalegreso6=number_format($sumaegreso6,2,",",".");
        $sumaegresos=number_format($sumatotalegreso,2,",",".");
        $sumatot_totalrecibir=number_format($sumatotalrecibir,2,",",".");

        $pdf->SetXY(($width / 4) * 2, $uno_y+5);
        $pdf->SetFont('Arial', '', 8);
        $margin = ($width / 4) * 2;
        $pdf->SetWidths(array(($width / 4), ($width / 6)));

        if($datos[0]->id_egreso1){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_egreso1)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos[0]->egreso1), $margin + 2, 'R');
        }
        if($datos[0]->id_egreso2){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_egreso2)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos[0]->egreso2), $margin + 2, 'R');
        }
        if($datos[0]->id_egreso3){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_egreso3)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('',$datos[0]->egreso3), $margin + 2, 'R');
        }
        if($datos[0]->id_egreso4){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_egreso4)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos[0]->egreso4), $margin + 2, 'R');
        }
        if($datos[0]->id_egreso5){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_egreso5)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos[0]->egreso5), $margin + 2, 'R');
        }
        if($datos[0]->id_egreso6){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos[0]->id_egreso6)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos[0]->egreso6), $margin + 2, 'R');
        }
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->RowWithoutImage(array('APORTE PERSONAL',''), $margin + 2, 'L');
        $pdf->SetXY($w_content + 5, $h_content);
        $pdf->RowWithoutImage(array('',$sumatot_iva), $margin + 2, 'R');
        $pdf->SetFont('Arial', 'B', 9);
        
        $pdf->RowWithoutImageWithBorder(array('TOTAL',$sumaegresos), $margin + 2, 'R','T');
        

        $y_egresos = $pdf->GetY();
        $total_neto=number_format($sumatotalrecibir,2,",",".");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('NETO A RECIBIR')) / 2), ($y_ingresos > $y_egresos ? $y_ingresos+5 : $y_egresos+5));
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('NETO A RECIBIR')), 8,utf8_decode('NETO A RECIBIR'), 0, 1, 'C', 0);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth($total_neto) / 2), $pdf->GetY());
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell($pdf->GetStringWidth($total_neto), 8,$total_neto, 0, 1, 'C', 0);

        $limit = $pdf->GetPageHeight() / 2;
        for ($i=0; $i < $width; $i++) { 
            $pdf->Line(($i * 4)+4, $limit, ($i * 4) + 5, $limit);
        }

        $pdf->SetY($limit - 15);
        $pdf->SetFont('Arial', 'B', 10);
        $footer_header = array('ELABORADO POR' ,'RECIBO CONFORME', 'REVISADO POR');
        $footer_header_widths = array($width / 3,$width / 3,$width / 3);
        $pdf->SetWidths($footer_header_widths);
        $pdf->RowWithoutImage($footer_header, 0);
        
        $pdf->SetFont('Arial', '', 9);
        $footer_values = array($empresa[0]->nombres.' '.$empresa[0]->apellidos, utf8_decode($datos[0]->primer_nombre),utf8_decode($empresa[0]->nomb_representante));
        $footer_values_widths = array($width / 3,$width / 3,$width / 3);
        $pdf->SetWidths($footer_values_widths);
        $pdf->RowWithoutImage($footer_values, 0);
        
        $pdf->Output("Rol_Pago_".$datos[0]->primer_nombre."_".$fecha_ini.".pdf","D");
    }
    public function RolProvicion($datos,$fecha_ini,$empresa,$proyecto){
        $pdf = new PDF_MC_Table('L', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $width = $pdf->GetPageWidth();
        $url=constant("DATA_EMPRESA");
        $margin = 8;
        $pdf->RoundedRect($margin, 10, $width - ($margin * 2), 20, 2, '1234', 'D');
        $logo=$url.$empresa[0]->id_empresa.'/imagen/'.$empresa[0]->logo; 
        if(strlen($empresa[0]->logo)>2 && file_exists($logo)){
            $pdf->Image($logo, $margin - 2, 10, ($width / 4)-2, 20);
        }else{
            $pdf->SetX(10);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 0, 'C', 0);
            $pdf->SetTextColor(0, 0, 0);
        }
        
        /*$pdf->Image($url.'/'.$empresa[0]->id_empresa.'/imagen/'.$empresa[0]->logo, $margin - 2, 8, $width / 4, 22);
        $pdf->SetFont('Arial', 'B', 14);

        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($empresa[0]->nombre_empresa)) / 2), 10);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($empresa[0]->nombre_empresa)), 8, utf8_decode($empresa[0]->nombre_empresa), 0, 2, 'C', 0);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('TRANSACIONES DE PROVISION')) / 2), 20);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('TRANSACIONES DE PROVISION')), 8, utf8_decode('TRANSACIONES DE PROVISION'), 0, 2, 'C', 0);*/
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($empresa[0]->nombre_empresa)) / 2), 10);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($empresa[0]->nombre_empresa)), 7, utf8_decode($empresa[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('ROL DE PROVISION')) / 2)+5, 17);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('ROL DE PROVISION')), 8, utf8_decode('ROL DE PROVISION'), 0, 2, 'C', 0);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('Proyecto: '.ucfirst($proyecto))) / 2), 23);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('Proyecto: '.ucfirst($proyecto))), 8, utf8_decode('Proyecto: '.ucfirst($proyecto)), 0, 2, 'C', 0);

        $pdf->SetXY(($width / 6) * 4.5, 11.5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Periodo:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Departamento: '), 0, 2, 'L', 0);
        $pdf->SetXY(($width / 6) * 5, 11.5);
        // $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        if ($fecha_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fecha_ini));
        }
        $pdf->Cell($pdf->GetStringWidth($fec_desde), 5, $fec_desde, 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth($pdf->PageNo() . ' de {nb}'), 5, $pdf->PageNo() . ' de {nb}' , 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->dep_nombre)), 5, utf8_decode($datos[0]->dep_nombre), 0, 2, 'L', 0);

        $pdf->Ln(20);
        $pdf->SetXY($margin,36);
        $pdf->SetFillColor(255, 255, 255);

        $count_widths = 9;
        $header_widths = array();
        $header_data = array(
            'CEDULA',
            'NOMBRE',
            'IESS PATRONAL',
            'DECIMO TERCERO',
            'DECIMO CUARTO',
            'FONDO RESERVA',
            'VACACIONES',
            'TOTAL PROVISIONES',
            'TOTAL COSTO'
        );

        for ($i=0; $i < $count_widths; $i++) {
            array_push($header_widths,(($width - ($margin * 2)) / $count_widths));
        }

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetWidths($header_widths);
        $pdf->RowWithoutImageWithBorder($header_data, $margin, 'R');
        $pdf->SetFont('Arial', '', 7);
        $total_iess=0;
        $total_decimo_tercero=0;
        $total_decimo_cuarto=0;
        $total_fondo_reserva=0;
        $total_vacaciones=0;
        $total_proviciones=0;
        $total_costo=0;
        $data = array();
        foreach ($datos as $detail) {
            array_push($data,$detail->dni);
            array_push($data,utf8_decode($detail->primer_nombre));
            array_push($data,$detail->iess_patronal);
            array_push($data,$detail->decimo_tercero);
            array_push($data,$detail->decimo_cuarto);
            array_push($data,$detail->fondo_reserva);
            array_push($data,$detail->vacaciones);
            array_push($data,$detail->total_provisiones);
            array_push($data,$detail->total_costo);
            $pdf->RowWithoutImage($data, $margin,'R', 1);
            $data = array();
            $pdf->Ln();

            $total_iess+=$detail->iess_patronal;
            $total_decimo_tercero+=$detail->decimo_tercero;
            $total_decimo_cuarto+=$detail->decimo_cuarto;
            $total_fondo_reserva+=$detail->fondo_reserva;
            $total_vacaciones+=$detail->vacaciones;
            $total_proviciones+=$detail->total_provisiones;
            $total_costo+=$detail->total_costo;
        }
        $sumaiess=number_format($total_iess,2,",",".");
        $sumadecimo_tercero=number_format($total_decimo_tercero,2,",",".");
        $sumadecimo_cuarto=number_format($total_decimo_cuarto,2,",",".");
        $sumafondo_reserva=number_format($total_fondo_reserva,2,",",".");
        $sumavacaciones=number_format($total_vacaciones,2,",",".");
        $sumaproviciones=number_format($total_proviciones,2,",",".");
        $sumacosto=number_format($total_costo,2,",",".");
        $pdf->SetFont('Arial', 'B', 7);
        $total_row = array(
            'Total',
            '',
            $sumaiess,
            $sumadecimo_tercero,
            $sumadecimo_cuarto,
            $sumafondo_reserva,
            $sumavacaciones,
            $sumaproviciones,
            $sumacosto
        );
        $pdf->SetX($margin);
        $pdf->RowWithoutImageWithBorder($total_row, $margin, 'R', 'TB');
        
        $pdf->SetXY(8,$pdf->GetY()+23);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('ELABORADO POR'),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('APROBADO POR'),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('REVISADO POR'),0,1,'C',0);
        $pdf->SetX(8);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nombres.' '.$empresa[0]->apellidos),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nomb_representante),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nombre_contador),0,1,'C',0);
        // $footer_header = array('ELABORADO POR' ,'APROBADO POR', 'REVISADO POR');
        // $footer_header_widths = array($width / 3,$width / 3,$width / 3);
        // $pdf->SetWidths($footer_header_widths);
        // $pdf->RowFooter($footer_header, 0);

        // $pdf->SetFont('Arial', '', 9);
        // $footer_values = array($empresa[0]->nombres.' '.$empresa[0]->apellidos ,utf8_decode($empresa[0]->nomb_representante), utf8_decode($empresa[0]->nombre_contador));
        // $footer_values_widths = array($width / 3,$width / 3,$width / 3);
        // $pdf->SetWidths($footer_values_widths);
        // $pdf->RowFooter($footer_values, 0);

        $pdf->Output("Rol_Provicion.pdf","D");

    }
    public function PapeletasIndividual($datos,$fecha_ini,$empresa){
        setlocale(LC_TIME, "spanish");
        $pdf = new PDF_Papeleta('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $sumasueldo=0.0;
        $sumaingreso1=0.0;
        $sumaingreso2=0.0;
        $sumaingreso3=0.0;
        $sumaingreso4=0.0;
        $sumaingreso5=0.0;
        $sumaingreso6=0.0;
        $sumadecimotercero=0.0;
        $sumadecimocuarto=0.0;
        $sumafondoreserva=0.0;
        $sumatotalingreso=0.0;
        $sumadecimal=0.0;
        //foreach ($datos as $detail) {
            $sumasueldo+=$datos->sueldo;
            $sumaingreso1+=$datos->ingreso1;
            $sumaingreso2+=$datos->ingreso2;
            $sumaingreso3+=$datos->ingreso3;
            $sumaingreso4+=$datos->ingreso4;
            $sumaingreso5+=$datos->ingreso5;
            $sumaingreso6+=$datos->ingreso6;
            $sumadecimotercero+=$datos->decimo_tercero;
            $sumadecimocuarto+=$datos->decimo_cuarto;
            $sumafondoreserva+=$datos->fondo_reserva;
            $sumatotalingreso+=$datos->total_ingreso;
        //}
        $sumatot_ingreso1=number_format($sumaingreso1,2,",",".");
        $sumatot_ingreso2=number_format($sumaingreso2,2,",",".");
        $sumatot_ingreso3=number_format($sumaingreso3,2,",",".");
        $sumatot_ingreso4=number_format($sumaingreso4,2,",",".");
        $sumatot_ingreso5=number_format($sumaingreso5,2,",",".");
        $sumatot_ingreso6=number_format($sumaingreso6,2,",",".");
        $sumatot_decimotercero=number_format($sumadecimotercero,2,",",".");
        $sumatot_decimocuarto=number_format($sumadecimocuarto,2,",",".");
        $sumatot_fondoreserva=number_format($sumafondoreserva,2,",",".");
        $sumatot_ingresos=number_format($sumatotalingreso,2,",",".");
        $length_ingreso1=strlen($datos->id_ingreso1)+8;
        $length_ingreso2=strlen($datos->id_ingreso2)+8;
        $length_ingreso3=strlen($datos->id_ingreso3)+8;
        $length_ingreso4=strlen($datos->id_ingreso4)+8;
        $length_ingreso5=strlen($datos->id_ingreso5)+8;
        $length_ingreso6=strlen($datos->id_ingreso6)+8;
        
        $width = $pdf->GetPageWidth();
        $margin = 4;
        $nombre_documento = 'ROL INDIVIDUAL';
        
        $url = constant("DATA_EMPRESA");
        $logo=$url.$empresa[0]->id_empresa.'/imagen/'.$empresa[0]->logo;
        if(strlen($empresa[0]->logo)>2 && file_exists($logo)){
            $pdf->Image($logo, $margin - 1, 5, ($width / 4) - ($margin * 2), 10);
        } else {
            $pdf->SetXY(2,2);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 0, 'C', 0);
            $pdf->SetTextColor(0, 0, 0);
        }
        //$pdf->Image($url.$empresa[0]->id_empresa.'/imagen/'.$empresa[0]->logo, $margin - 1, 5, ($width / 4) - ($margin * 2), 10);
        $pdf->SetFont('Arial', 'B', 14);
        if($pdf->GetStringWidth(utf8_decode($datos->nombre_empresa))){
            $pdf->SetXY(47, 7);
            $pdf->MultiCell(160, 4, utf8_decode($datos->nombre_empresa), 0, 'C', 0);
            $head_empresa_y=$pdf->GetY();
        }else{
            
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($datos->nombre_empresa)) / 2), 7);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos->nombre_empresa)), 4, utf8_decode($datos->nombre_empresa), 0, 2, 'C', 0);
        }
        
        if($pdf->GetStringWidth(utf8_decode($datos->nombre_empresa))){
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($nombre_documento)) / 2), $head_empresa_y);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($nombre_documento)), 8, utf8_decode($nombre_documento), 0, 2, 'C', 0);
        }else{
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($nombre_documento)) / 2), 10);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($nombre_documento)), 8, utf8_decode($nombre_documento), 0, 2, 'C', 0);
        }
        
        
        $pdf->SetXY($margin + 2, 17);
        
        $codigo=0;
        if($datos->cod_rol_pago<10){
            $codigo='0000'.$datos->cod_rol_pago;
        }else{
            if($datos->cod_rol_pago<100){
                $codigo='000'.$datos->cod_rol_pago;
            }else{
                if($datos->cod_rol_pago<1000){
                    $codigo='00'.$datos->cod_rol_pago;
                }else{
                    if($datos->cod_rol_pago<10000){
                        $codigo='0'.$datos->cod_rol_pago;
                    }else{
                        $codigo=$datos->cod_rol_pago;
                    }
                }
                        
            }
        }
        if ($fecha_ini != null) {
            $fec_desde = strftime("%B del %G", strtotime($fecha_ini));
        }
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetWidths(array(($width / 9) - 5 , ($width / 6),($width / 3),($width / 9),($width / 8), ($width / 6)));
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->HeadWithoutImage(array('Codigo:','','','','Periodo:',''), 4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, $h_content);
        $pdf->HeadWithoutImage(array('',$codigo,'','','',$fec_desde), 4);
        $pdf->SetFont('Arial', 'B', 8);
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->HeadWithoutImage(array('Nombre:','','','','Dias Trabajados:',''), 4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, $h_content );
        $pdf->HeadWithoutImage(array('',utf8_decode($datos->primer_nombre),'','','',$datos->cantidad), 4);
        $pdf->SetFont('Arial', 'B', 8);
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->HeadWithoutImage(array('Ciudad:','','','','Departamento:',''), 4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, $h_content);
        $pdf->HeadWithoutImage(array('',utf8_decode('Pichincha'),'','','',utf8_decode($datos->dep_nombre)), 4);
        
        $dep=$pdf->GetY();
        $pdf->RoundedRect($margin, 4, $width - ($margin * 2), $dep + 1, 2, '1234', 'D');

        //------------------------------Ingresos
        $length_ingreso1=strlen($datos->id_ingreso1)+8;
        $length_ingreso2=strlen($datos->id_ingreso2)+8;
        $length_ingreso3=strlen($datos->id_ingreso3)+8;
        $length_ingreso4=strlen($datos->id_ingreso4)+8;
        $length_ingreso5=strlen($datos->id_ingreso5)+8;
        $length_ingreso6=strlen($datos->id_ingreso6)+8;

        $h_content = $pdf->GetY() + 6;
        $uno_y = $pdf->GetY() + 6;
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetY($h_content);
        $pdf->SetX(0);
        
        $pdf->SetWidths(array(($width / 2), ($width / 2)));
        $pdf->RowWithoutImage(array('INGRESO','EGRESO'), 0);
        
        $pdf->SetXY(10, $h_content + 5);
        
        $pdf->SetFont('Arial', '', 8);

        $pdf->SetWidths(array(($width / 4), ($width / 6)));
        $pdf->RowWithoutImage(array(utf8_decode('SUELDO'),""), $margin + 2, 'L');
        
        $pdf->SetXY(10, $h_content + 5);
        $pdf->RowWithoutImage(array("", $datos->sueldo), $margin + 2, 'R');
        

        if($datos->id_ingreso1){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_ingreso1)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso1), $margin + 2, 'R');
        }
        if($datos->id_ingreso2){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_ingreso2)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso2), $margin + 2, 'R');
        }
        if($datos->id_ingreso3){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_ingreso3)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso3), $margin + 2, 'R');
        }
        if($datos->id_ingreso4){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_ingreso4)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso4), $margin + 2, 'R');
        }
        if($datos->id_ingreso5){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_ingreso5)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso5), $margin + 2, 'R');
        }
        if($datos->id_ingreso6){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_ingreso6)), ""), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array("", $sumatot_ingreso6), $margin + 2, 'R');
        }
        if($sumadecimotercero!=0){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array('DECIMO TERCERO', ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $sumatot_decimotercero), $margin + 2, 'R');
        }
        if($sumadecimocuarto!=0){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array('DECIMO CUARTO', ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $sumatot_decimocuarto), $margin + 2, 'R');
        }
        if($sumafondoreserva!=0){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array('FONDO RESERVA', ''), $margin + 2, 'R');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $sumatot_fondoreserva), $margin + 2, 'R');
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->RowWithoutImageWithBorder(array('TOTAL', $sumatot_ingresos), $margin + 2, 'R', 'T');
        $y_ingresos = $pdf->GetY();
        //--------------------------------Egresos
        //-----------------valores egresos
        $sumaiva=0;
        $sumaegreso1=0;
        $sumaegreso2=0;
        $sumaegreso3=0;
        $sumaegreso4=0;
        $sumaegreso5=0;
        $sumaegreso6=0;
        $sumatotalegreso=0;
        $sumatotalrecibir=0;
        $sumatotalegreso1=0;
        $sumatotalegreso2=0;
        $sumatotalegreso3=0;
        $sumatotalegreso4=0;
        $sumatotalegreso5=0;
        $sumatotalegreso6=0;
        //----------------longitud egresos
        $length_egreso1=strlen($datos->id_egreso1)+8;
        $length_egreso2=strlen($datos->id_egreso2)+8;
        $length_egreso3=strlen($datos->id_egreso3)+8;
        $length_egreso4=strlen($datos->id_egreso4)+8;
        $length_egreso5=strlen($datos->id_egreso5)+8;
        $length_egreso6=strlen($datos->id_egreso6)+8;
        //foreach($datos as $detail){
            $sumaiva+=$datos->iess;
            $sumaegreso1+=$datos->egreso1;
            $sumaegreso2+=$datos->egreso2;
            $sumaegreso3+=$datos->egreso3;
            $sumaegreso4+=$datos->egreso4;
            $sumaegreso5+=$datos->egreso5;
            $sumaegreso6+=$datos->egreso6;
            $sumatotalegreso+=$datos->total_egreso;
            $sumatotalrecibir+=$datos->valor_recibir;
        //}
        $sumatot_iva=number_format($sumaiva,2,",",".");
        $sumatotalegreso1=number_format($sumaegreso1,2,",",".");
        $sumatotalegreso2=number_format($sumaegreso2,2,",",".");
        $sumatotalegreso3=number_format($sumaegreso3,2,",",".");
        $sumatotalegreso4=number_format($sumaegreso4,2,",",".");
        $sumatotalegreso5=number_format($sumaegreso5,2,",",".");
        $sumatotalegreso6=number_format($sumaegreso6,2,",",".");
        $sumaegresos=number_format($sumatotalegreso,2,",",".");
        $sumatot_totalrecibir=number_format($sumatotalrecibir,2,",",".");

        $pdf->SetXY(($width / 4) * 2, $uno_y+5);
        $pdf->SetFont('Arial', '', 8);
        $margin = ($width / 4) * 2;
        $pdf->SetWidths(array(($width / 4), ($width / 6)));

        if($datos->id_egreso1){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_egreso1)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos->egreso1), $margin + 2, 'R');
        }
        if($datos->id_egreso2){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_egreso2)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos->egreso2), $margin + 2, 'R');
        }
        if($datos->id_egreso3){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_egreso3)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('',$datos->egreso3), $margin + 2, 'R');
        }
        if($datos->id_egreso4){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_egreso4)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos->egreso4), $margin + 2, 'R');
        }
        if($datos->id_egreso5){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_egreso5)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos->egreso5), $margin + 2, 'R');
        }
        if($datos->id_egreso6){
            $h_content = $pdf->GetY();
            $w_content=  $pdf->GetX();
            $pdf->RowWithoutImage(array(strtoupper(utf8_decode($datos->id_egreso6)), ''), $margin + 2, 'L');
            $pdf->SetXY($w_content + 5, $h_content);
            $pdf->RowWithoutImage(array('', $datos->egreso6), $margin + 2, 'R');
        }
        $h_content = $pdf->GetY();
        $w_content=  $pdf->GetX();
        $pdf->RowWithoutImage(array('APORTE PERSONAL',''), $margin + 2, 'L');
        $pdf->SetXY($w_content + 5, $h_content);
        $pdf->RowWithoutImage(array('',$sumatot_iva), $margin + 2, 'R');
        $pdf->SetFont('Arial', 'B', 9);
        
        $pdf->RowWithoutImageWithBorder(array('TOTAL',$sumaegresos), $margin + 2, 'R','T');
        

        $y_egresos = $pdf->GetY();
        $total_neto=number_format($sumatotalrecibir,2,",",".");
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('NETO A RECIBIR')) / 2), ($y_ingresos > $y_egresos ? $y_ingresos+5 : $y_egresos+5));
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('NETO A RECIBIR')), 8,utf8_decode('NETO A RECIBIR'), 0, 1, 'C', 0);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth($total_neto) / 2), $pdf->GetY());
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell($pdf->GetStringWidth($total_neto), 8,$total_neto, 0, 1, 'C', 0);

        $limit = $pdf->GetPageHeight() / 2;
        for ($i=0; $i < $width; $i++) { 
            $pdf->Line(($i * 4)+4, $limit, ($i * 4) + 5, $limit);
        }

        $pdf->SetY($limit - 15);
        $pdf->SetFont('Arial', 'B', 10);
        $footer_header = array('ELABORADO POR' ,'RECIBO CONFORME', 'REVISADO POR');
        $footer_header_widths = array($width / 3,$width / 3,$width / 3);
        $pdf->SetWidths($footer_header_widths);
        $pdf->RowWithoutImage($footer_header, 0);
        
        $pdf->SetFont('Arial', '', 9);
        $footer_values = array($empresa[0]->nombres.' '.$empresa[0]->apellidos, utf8_decode($datos->primer_nombre),utf8_decode($empresa[0]->nomb_representante));
        $footer_values_widths = array($width / 3,$width / 3,$width / 3);
        $pdf->SetWidths($footer_values_widths);
        $pdf->RowWithoutImage($footer_values, 0);
        
        $url = constant("DATA_EMPRESA");
        $mes="Febrero 2020";
        $fecha_papeleta=ucwords(strftime("%B %Y", strtotime($fecha_ini)));
        $documento="Rol_Pago_".$datos->primer_nombre."_".$fecha_papeleta.".pdf";
        $url_pdf= $url.$datos->id_empresa.'/papeletas/'.$fecha_papeleta.'/'.$datos->id_departamento.'/'.$documento;
        $pdf->Output("F",$url_pdf);
    }
    public function PdfRolGeneral($datos,$fecha_ini,$empresa,$nombre_proyecto){
        setlocale(LC_TIME, "spanish");
        $pdf = new PDF_MC_Table('L', 'mm', 'A4');
        $width = $pdf->GetPageWidth();
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $url=constant("DATA_EMPRESA");
        $pdf->RoundedRect(8, 10, 279, 20, 2, '1234', 'D');
        $logo=$url.$empresa[0]->id_empresa.'/imagen/'.$empresa[0]->logo;
        //$pdf->Image(, 9, 8, 75, 23);
        if ($empresa[0]->logo!==null && file_exists($logo)) {
            $pdf->Image($logo, 9, 8, 75, 23);
            
        } else {
            $pdf->SetX(10);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 0, 'C', 0);
            $pdf->SetTextColor(0, 0, 0);
        }
        $pdf->SetFont('Arial', 'B', 14);
        //$pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)) / 2), 10);
        //$pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)), 8, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        if($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa))>164){
            $pdf->SetXY(70, 10.5);
            $pdf->MultiCell(164, 4, utf8_decode($datos[0]->nombre_empresa), 0, 'C', 0);
        }else{
            $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)) / 2), 10);
            $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->nombre_empresa)), 7, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('ROL PAGO GENERAL')) / 2), 17);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('ROL PAGO GENERAL')), 8, utf8_decode('ROL PAGO GENERAL'), 0, 2, 'C', 0);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('Proyecto: '.ucfirst($nombre_proyecto))) / 2), 23);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('Proyecto: '.ucfirst($nombre_proyecto))), 8, utf8_decode('Proyecto: '.ucfirst($nombre_proyecto)), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        
        $pdf->SetXY(($width / 6) * 4.5, 12.5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Periodo:'), 0, 3, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 3, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Departamento: '), 0, 3, 'L', 0);
        $pdf->SetXY(($width / 6) * 5, 12.5);
        // $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        if ($fecha_ini != null) {
            $fec_desde = strftime("%B del %G", strtotime($fecha_ini));
        }
        $pdf->Cell($pdf->GetStringWidth($fec_desde), 5, $fec_desde, 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth($pdf->PageNo() . ' de {nb}'), 5, $pdf->PageNo() . ' de {nb}' , 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($datos[0]->dep_nombre)), 5, utf8_decode($datos[0]->dep_nombre), 0, 2, 'L', 0);


        $pdf->Ln(20);
        $sumasueldo=0.0;
        $sumaingreso1=0.0;
        $sumaingreso2=0.0;
        $sumaingreso3=0.0;
        $sumaingreso4=0.0;
        $sumaingreso5=0.0;
        $sumaingreso6=0.0;
        $sumadecimotercero=0.0;
        $sumadecimocuarto=0.0;
        $sumafondoreserva=0.0;
        $sumatotalingreso=0.0;
        $sumadecimal=0.0;
        foreach ($datos as $detail) {
            $sumasueldo+=$detail->sueldo;
            $sumaingreso1+=$detail->ingreso1;
            $sumaingreso2+=$detail->ingreso2;
            $sumaingreso3+=$detail->ingreso3;
            $sumaingreso4+=$detail->ingreso4;
            $sumaingreso5+=$detail->ingreso5;
            $sumaingreso6+=$detail->ingreso6;
            $sumadecimotercero+=$detail->decimo_tercero;
            $sumadecimocuarto+=$detail->decimo_cuarto;
            $sumafondoreserva+=$detail->fondo_reserva;
            $sumatotalingreso+=$detail->total_ingreso;
        }
        $sumatot_ingreso1=number_format($sumaingreso1,2,".",",");
        $sumatot_ingreso2=number_format($sumaingreso2,2,".",",");
        $sumatot_ingreso3=number_format($sumaingreso3,2,".",",");
        $sumatot_ingreso4=number_format($sumaingreso4,2,".",",");
        $sumatot_ingreso5=number_format($sumaingreso5,2,".",",");
        $sumatot_ingreso6=number_format($sumaingreso6,2,".",",");
        $sumadecimotercero;
        $sumadecimocuarto;
        $sumafondoreserva;
        $sumatotalingreso;
        $length_ingreso1=strlen($datos[0]->id_ingreso1)+15;
        $length_ingreso2=strlen($datos[0]->id_ingreso2)+15;
        $length_ingreso3=strlen($datos[0]->id_ingreso3)+15;
        $length_ingreso4=strlen($datos[0]->id_ingreso4)+15;
        $length_ingreso5=strlen($datos[0]->id_ingreso5)+15;
        $length_ingreso6=strlen($datos[0]->id_ingreso6)+15;
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('INGRESOS')) / 2), 35);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('Ingresos')), 8, utf8_decode('INGRESOS'), 0, 2, 'C', 0);

        $pdf->Ln(20);
        //HEADER FOR TABLE
        $pdf->SetY(45);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $header_data = array('CEDULA', 'NOMBRE', 'SUELDO');
        // $header_widths = array(20,25,15);
        $header_widths = array();
        $count_width = 4;

        if($datos[0]->id_ingreso1){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso1)));
            // array_push($header_widths, $length_ingreso1);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso2){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso2)));
            // array_push($header_widths, $length_ingreso2);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso3){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso3)));
            // array_push($header_widths, $length_ingreso3);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso4){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso4)));
            // array_push($header_widths, $length_ingreso4);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso5){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso5)));
            // array_push($header_widths, $length_ingreso5);
            $count_width = $count_width + 1;
        }
        if($datos[0]->id_ingreso6){
            array_push($header_data, strtoupper(utf8_decode($datos[0]->id_ingreso6)));
            // array_push($header_widths, $length_ingreso6);
            $count_width = $count_width + 1;
        }
        if($sumadecimotercero!=0){
            array_push($header_data, 'DECIMO TERCERO');
            // array_push($header_widths, 25);
            $count_width = $count_width + 1;
        }
        if($sumadecimocuarto!=0){
            array_push($header_data, 'DECIMO CUARTO');
            // array_push($header_widths, 25);
            $count_width = $count_width + 1;
        }
        if($sumafondoreserva!=0){
            array_push($header_data, 'FONDO RESERVA');
            // array_push($header_widths, 25);
            $count_width = $count_width + 1;
        }
        array_push($header_data, 'TOTAL INGRESOS');
        // array_push($header_widths, 25);
        for ($i=0; $i < $count_width; $i++) {
            array_push($header_widths,(($width - 16) / $count_width));
        }
        $pdf->SetX(8);
        $pdf->SetWidths($header_widths);
        $pdf->RowWithoutImageWithBorder($header_data, 8);

        //fill cells
        $tsant = 0;
        $tdebe = 0;
        $thaber = 0;
        $tsactual = 0;
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetX(8);
        foreach ($datos as $detail) {
            $data = array(
                $detail->dni,
                utf8_decode($detail->primer_nombre),
                $detail->sueldo
            );
            if($sumatot_ingreso1!=0){
                array_push($data,$detail->ingreso1);
            }
            if($sumatot_ingreso2!=0){
                array_push($data,$detail->ingreso2);
            }
            if($sumatot_ingreso3!=0){
                array_push($data,$detail->ingreso3);
            }
            if($sumatot_ingreso4!=0){
                array_push($data,$detail->ingreso4);
            }
            if($sumatot_ingreso5!=0){
                array_push($data,$detail->ingreso5);
            }
            
            if($sumatot_ingreso6!=0){
                array_push($data,$detail->ingreso6);
            }/*else{
                array_push($data,);
            }*/
            
            if($sumadecimotercero!=0){
                array_push($data, $detail->decimo_tercero);
            }
            if($sumadecimocuarto!=0){
                array_push($data, $detail->decimo_cuarto);
            }
            if($sumafondoreserva!=0){
                array_push($data, $detail->fondo_reserva);
            }
            array_push($data,$detail->total_ingreso);
            //$pdf->RowWithoutImage($data, 8, 'C', 3);
            $pdf->RowData($data, 8, 'C', 3);
        }

        $sumadecimal=number_format($sumasueldo,2,".",",");
        $total_data = array('Total', '', $sumadecimal,);
        if($datos[0]->id_ingreso1){
            array_push($total_data, $sumatot_ingreso1);
        }
        if($datos[0]->id_ingreso2){
            array_push($total_data, $sumatot_ingreso2);
        }
        if($datos[0]->id_ingreso3){
            array_push($total_data, $sumatot_ingreso3);
        }
        if($datos[0]->id_ingreso4){
            array_push($total_data, $sumatot_ingreso4);
        }
        if($datos[0]->id_ingreso5){
            array_push($total_data, $sumaingreso5);
        }
        if($datos[0]->id_ingreso6){
            array_push($total_data, $sumaingreso6);
        }
        if($sumadecimotercero!=0){
            array_push($total_data, $sumadecimotercero);
        }
        if($sumadecimocuarto!=0){
            array_push($total_data, $sumadecimocuarto);
        }
        if($sumafondoreserva!=0){
            array_push($total_data, $sumafondoreserva);
        }
        array_push($total_data, $sumatotalingreso);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetX(8);
        $pdf->RowWithoutImageWithBorder($total_data, 8,'R');
        $head_ingresos=$pdf->GetY();
        if($head_ingresos>105){
            $otro_valor=$pdf->GetPageHeight()-$head_ingresos;
            $pdf->SetAutoPageBreak(true,$otro_valor);
        }
        $sumaiva=0;
        $sumaegreso1=0;
        $sumaegreso2=0;
        $sumaegreso3=0;
        $sumaegreso4=0;
        $sumaegreso5=0;
        $sumaegreso6=0;
        $sumatotalegreso=0;
        $sumatotalrecibir=0;
        $sumatotalegreso1=0;
        $sumatotalegreso2=0;
        $sumatotalegreso3=0;
        $sumatotalegreso4=0;
        $sumatotalegreso5=0;
        $sumatotalegreso6=0;
        $length_egreso1=strlen($datos[0]->id_egreso1)+15;
        $length_egreso2=strlen($datos[0]->id_egreso2)+15;
        $length_egreso3=strlen($datos[0]->id_egreso3)+15;
        $length_egreso4=strlen($datos[0]->id_egreso4)+15;
        $length_egreso5=strlen($datos[0]->id_egreso5)+15;
        $length_egreso6=strlen($datos[0]->id_egreso6)+15;
        foreach($datos as $detail){
            $sumaiva+=$detail->iess;
            $sumaegreso1+=$detail->egreso1;
            $sumaegreso2+=$detail->egreso2;
            $sumaegreso3+=$detail->egreso3;
            $sumaegreso4+=$detail->egreso4;
            $sumaegreso5+=$detail->egreso5;
            $sumaegreso6+=$detail->egreso6;
            $sumatotalegreso+=$detail->total_egreso;
            $sumatotalrecibir+=$detail->valor_recibir;
        }
        number_format($sumaiva,2,".",",");
        $sumatotalegreso1=number_format($sumaegreso1,2,".",",");
        $sumatotalegreso2=number_format($sumaegreso2,2,".",",");
        $sumatotalegreso3=number_format($sumaegreso3,2,".",",");
        $sumatotalegreso4=number_format($sumaegreso4,2,".",",");
        $sumatotalegreso5=number_format($sumaegreso5,2,".",",");
        $sumatotalegreso6=number_format($sumaegreso6,2,".",",");
        number_format($sumatotalegreso,2,".",",");
        $sumatotalrecibir;

        $pdf->SetFont('Arial', 'B', 10);

        $pdf->SetY($pdf->GetY()+5);
        $pdf->SetX(($width / 2) - ($pdf->GetStringWidth(utf8_decode('EGRESOS')) / 2));
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('EGRESOS')), 8, utf8_decode('EGRESOS'), 0, 2, 'C', 0);

        $egresos_data = array('CEDULA', 'NOMBRE');
        $egresos_widths = array();

        $pdf->SetX(8);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $count_egresos = 5;

        if($datos[0]->id_egreso1){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso1)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso2){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso2)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso3){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso3)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso4){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso4)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso5){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso5)));
            $count_egresos = $count_egresos + 1;
        }
        if($datos[0]->id_egreso6){
            array_push($egresos_data, strtoupper(utf8_decode($datos[0]->id_egreso6)));
            $count_egresos = $count_egresos + 1;
        }
        array_push($egresos_data, 'IESS 9,45%');
        array_push($egresos_data, 'TOTAL EGRESO');
        array_push($egresos_data, 'VALOR A RECIBIR');

        for ($i=0; $i < $count_egresos; $i++) {
            array_push($egresos_widths,(($width - 16) / $count_egresos));
        }

        $pdf->SetWidths($egresos_widths);
        $pdf->RowWithoutImageWithBorder($egresos_data, 8);

        $pdf->SetFont('Arial', '', 7);
        // $pdf->SetY(70);
        $egresos_values = array();
        $pdf->SetX(8);
        
        foreach ($datos as $detail) {
            array_push($egresos_values, $detail->dni);
            array_push($egresos_values, utf8_decode($detail->primer_nombre));
            //$detail->id_egreso1 || $detail->egreso1 ?  array_push($egresos_values, $detail->egreso1)
            if($sumatotalegreso1!=0){
                array_push($egresos_values, $detail->egreso1);
            }
            if($sumatotalegreso2!=0){
                array_push($egresos_values, $detail->egreso2);
            }
            if($sumatotalegreso3!=0){
                array_push($egresos_values, $detail->egreso3);
            }
            if($sumatotalegreso4!=0){
                array_push($egresos_values, $detail->egreso4);
            }
            if($sumatotalegreso5!=0){
                array_push($egresos_values, $detail->egreso5);
            }
            if($sumatotalegreso6!=0){
                array_push($egresos_values, $detail->egreso6);
            }
            array_push($egresos_values, $detail->iess);
            array_push($egresos_values, $detail->total_egreso);
            array_push($egresos_values, $detail->valor_recibir);
            //$pdf->RowWithoutImage($egresos_values, 8, 'C', 3);
            $pdf->RowData($egresos_values, 8, 'C', 3);
            $egresos_values = array();
        }
        $egresos_total = array();
        $egresos_total_widths = array();

        array_push($egresos_total, 'Total');
        array_push($egresos_total, '');
        if($datos[0]->id_egreso1){
            array_push($egresos_total, $sumatotalegreso1);
        }
        if($datos[0]->id_egreso2){
            array_push($egresos_total, $sumatotalegreso2);
        }
        if($datos[0]->id_egreso3){
            array_push($egresos_total, $sumatotalegreso3);
        }
        if($datos[0]->id_egreso4){
            array_push($egresos_total, $sumatotalegreso4);
        }
        if($datos[0]->id_egreso5){
            array_push($egresos_total, $sumatotalegreso5);
        }
        if($datos[0]->id_egreso6){
            array_push($egresos_total, $sumatotalegreso6);
        }
        array_push($egresos_total, $sumaiva);
        array_push($egresos_total, $sumatotalegreso);
        array_push($egresos_total, $sumatotalrecibir);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->RowWithoutImageWithBorder($egresos_total, 8,'R');

        $departamento=utf8_decode($datos[0]->dep_nombre);
        $head_egresos=$pdf->GetY();
        if($head_egresos<171){
            $otro_valor=$pdf->GetPageHeight()-$head_egresos;
            $pdf->SetAutoPageBreak(true,2);
        }
        $pdf->SetXY(8,$pdf->GetY()+15);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('ELABORADO POR'),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('APROBADO POR'),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode('REVISADO POR'),0,1,'C',0);
        $pdf->SetX(8);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nombres.' '.$empresa[0]->apellidos),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nomb_representante),0,0,'C',0);
        $pdf->Cell(($width / 3)-9+3.6,5,utf8_decode($empresa[0]->nombre_contador),0,1,'C',0);
        // $pdf->SetY($pdf->GetY() + 10);
        // $pdf->SetFont('Arial', 'B', 10);
        // $footer_header = array('ELABORADO POR' ,'APROBADO POR', 'REVISADO POR');
        // $footer_header_widths = array($width / 3,$width / 3,$width / 3);
        // $pdf->SetWidths($footer_header_widths);
        // $pdf->RowWithoutImage($footer_header, 0);
        
        // $pdf->SetFont('Arial', '', 9);
        // $footer_values = array($empresa[0]->nombres.' '.$empresa[0]->apellidos ,utf8_decode($empresa[0]->nomb_representante), utf8_decode($empresa[0]->nombre_contador));
        // $footer_values_widths = array($width / 3,$width / 3,$width / 3);
        // $pdf->SetWidths($footer_values_widths);
        // $pdf->RowWithoutImage($footer_values, 0);
        $url = constant("DATA_EMPRESA");
        $mes="Febrero 2020";
        $fecha_papeleta=ucwords(strftime("%B %Y", strtotime($fecha_ini)));
        $documento="Rol_Pago_General_".$fecha_papeleta.".pdf";
        $url_pdf= $url.$empresa[0]->id_empresa.'/rol_general/'.$fecha_papeleta.'/'.$datos[0]->id_departamento.'/'.$documento;
        $pdf->Output("F",$url_pdf);
    }
    public function PDFConciliacionBancaria($datos,$empresa,$nomcta,$codcta,$bansel,$nombre_banco,$ruta=null){
        setlocale(LC_TIME, "spanish");
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $width = $pdf->GetPageWidth();
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $url=constant("DATA_EMPRESA");
        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        //$pdf->Image($url.$empresa[0]->id_empresa.'/imagen/'.$empresa[0]->logo, 12, 11, 40, 18);
        $logo = constant("DATA_EMPRESA")  . $empresa[0]->id_empresa . '/imagen/' . $empresa[0]->logo;
        if (strlen($empresa[0]->logo)>0 && file_exists($logo)) {
            //$pdf->Image($logo, 12, 12, 55, 20);
            $pdf->Image($logo, 12, 11, 40, 18);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
            
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode($empresa[0]->nombre_empresa)) / 2), 12);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode($empresa[0]->nombre_empresa)), 8, utf8_decode($empresa[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(utf8_decode('Conciliacion Bancaria')) / 2), 17);
        $pdf->Cell($pdf->GetStringWidth(utf8_decode('Conciliacion Bancaria')), 8,utf8_decode('Conciliacion Bancaria'), 0, 2, 'C', 0);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth(ucwords(strftime("%B %Y", strtotime($datos[0]->fecha_conciliacion)))) / 2), 22);
        $pdf->Cell($pdf->GetStringWidth(ucwords(strftime("%B %Y", strtotime($datos[0]->fecha_conciliacion)))), 8, ucwords(strftime("%B %Y", strtotime($datos[0]->fecha_conciliacion))), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        
        $pdf->SetXY(($width / 6) * 4.5, 11.5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 4, "", 0, 2, 'L', 0);
        $pdf->Cell(15, 5, "Fecha:", 0, 2, 'L', 0);
        //$pdf->Cell(15, 5, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, "", 0, 2, 'L', 0);
        $pdf->SetXY(($width / 6) * 5, 11.5);
        // $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        //if ($fecha_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($datos[0]->fecha_reguistro));
            $fec_hasta = date("d/m/Y", strtotime($datos[0]->fecha_conciliacion));
        //}
        $pdf->Cell(15, 4, "", 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth($fecha_actual), 5, $fecha_actual, 0, 2, 'L', 0);
        //$pdf->Cell($pdf->GetStringWidth($fec_desde), 5, $fec_desde, 0, 2, 'L', 0);
        //$pdf->Cell($pdf->GetStringWidth($fec_hasta), 5, $fec_hasta, 0, 2, 'L', 0);
        $pdf->Cell($pdf->GetStringWidth($pdf->PageNo() . ' de {nb}'), 5, $pdf->PageNo() . ' de {nb}' , 0, 2, 'L', 0);
        $pdf->Cell(15, 5, "", 0, 2, 'L', 0);

        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(190, 6, $codcta." - ".utf8_decode($nomcta), 0, 0, 'C', 0);
        $pdf->Ln();
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 8);
        if($bansel==1){
            $pdf->Cell(63, 6, "Cuenta Corriente", 0, 0, 'C', 0); 
        }else{
            $pdf->Cell(63, 6, "Cuenta Ahorros", 0, 0, 'C', 0); 
        }
        $pdf->Cell(63, 6, "", 0, 0, 'C', 0);
        $pdf->Cell(63, 6, utf8_decode($nombre_banco), 0, 0, 'C', 0);
        $pdf->Ln();
        $existe_cheque_banco=0;
        $existe_transferencia_banco=0;
        $existe_deposito_banco=0;
        $existe_nota_credito=0;
        $existe_nota_debito=0;
        $existe_cheque_libro=0;
        $existe_transferencia_libro=0;
        $existe_deposito_libro=0;
        foreach($datos as $detail){
            $codigo=substr($detail->codigo_comprobante,0,1);
            if($detail->descripcionfp=="Cheque" && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                $existe_cheque_banco++; 
            }
            if($detail->descripcionfp=="Transferencia" && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                $existe_transferencia_banco++;
            }
            if($detail->descripcionfp=="Deposito"  && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                $existe_deposito_banco++;
            }
            if($detail->descripcionfp=="Nota de Credito"  && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                $existe_nota_credito++;
            }
            if($detail->descripcionfp=="Nota de Debito"  && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                $existe_nota_debito++;
            }
            if($detail->tipo_conciliacion=="Cheque en Libro"){
                $existe_cheque_libro++;
            }
            if($detail->tipo_conciliacion=="Transferencia en Libro"){
                $existe_transferencia_libro++;
            }
            if($detail->tipo_conciliacion=="Deposito en Libro"){
                $existe_deposito_libro++;
            }
        }
        //dd($existe_cheque_libro);

        $suma_cheque_banco=0;
        $suma_transferencia_banco=0;
        $suma_deposito_banco=0;
        $suma_nota_credito=0;
        $suma_nota_debito=0;
        $suma_cheque_libro=0;
        $suma_transferencia_libro=0;
        $suma_deposito_libro=0;
        $pdf->SetX(10);
        $head=$pdf->GetY();
        if($existe_cheque_banco>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(+) Cheques girados y no Cobrados", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->descripcionfp=="Cheque" && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                    //$suma_cheque_banco+=$detail->haber;
                    $suma_cheque_banco+=$detail->haber+$detail->debe;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format($detail->haber+$detail->debe,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_cheque_banco,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        if($existe_transferencia_banco>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(+) Transferencias no reguistradas en Bancos", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->descripcionfp=="Transferencia" && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                    //$suma_transferencia_banco+=$detail->haber;
                    $suma_transferencia_banco+=0-$detail->haber-$detail->debe;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format(0-$detail->haber-$detail->debe,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_transferencia_banco,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        if($existe_deposito_banco>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(+) Depositos no Reguistrados en Bancos", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->descripcionfp=="Deposito"  && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                    //$suma_deposito_banco+=$detail->debe;
                    $suma_deposito_banco+=$detail->debe+$detail->haber;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format($detail->debe+$detail->haber,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_deposito_banco,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        if($existe_nota_credito>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(+) Nota Credito no Reguistrados en Bancos", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->descripcionfp=="Nota de Credito"  && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                    //$suma_nota_credito+=$detail->debe;
                    $suma_nota_credito+=$detail->debe+$detail->haber;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format($detail->debe+$detail->haber,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_nota_credito,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        if($existe_nota_debito>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(+) Nota Debito no Reguistrados en Bancos", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->descripcionfp=="Nota de Debito"  && $detail->conciliación==null && $detail->tipo_conciliacion==null){
                    //$suma_nota_debito+=$detail->debe;
                    $suma_nota_debito+=$detail->debe+$detail->haber;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format($detail->debe+$detail->haber,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_nota_debito,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        if($existe_cheque_libro>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(-) Cheques no reguistrados en Libros", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->conciliación==null && $detail->tipo_conciliacion=="Cheque en Libro"){
                    $suma_cheque_libro+=$detail->haber;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format($detail->haber,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_cheque_libro,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        if($existe_transferencia_libro>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(-) Transferencias no reguistradas en Libros", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->conciliación==null && $detail->tipo_conciliacion=="Transferencia en Libro"){
                    $suma_transferencia_libro+=$detail->haber;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format($detail->haber,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_transferencia_libro,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        if($existe_deposito_libro>0){
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "(-) Depositos no reguistrados en Libros", 0, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(25, 6, "Fecha Reguistro", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Fecha Pago", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Nro Asiento", 0, 0, 'C', 0);
            $pdf->Cell(25, 6, "Nro Comprobante", 0, 0, 'C', 0);
            $pdf->Cell(75, 6, "Descripcion", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "Valor", 0, 0, 'C', 0);
            $pdf->Ln();
            foreach($datos as $detail){
                $codigo=substr($detail->codigo_comprobante,0,1);
                $pdf->SetX(10);
                if($detail->conciliación==null && $detail->tipo_conciliacion=="Deposito en Libro"){
                    $suma_deposito_libro+=$detail->debe;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_reguistro)), 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, date("d/m/Y", strtotime($detail->fecha_de_pago)), 0, 0, 'C', 0);
                    $pdf->Cell(20, 3, $detail->codigo_comprobante, 0, 0, 'C', 0);
                    $pdf->Cell(25, 3, $detail->no_documento, 0, 0, 'C', 0);
                    $pdf->Cell(75, 3, utf8_decode($detail->concepto), 0, 0, 'L', 1);
                    $pdf->Cell(20, 3, "$".number_format($detail->debe,2,".",","), 0, 0, 'R', 1);
                    $pdf->Cell(20, 3, "", 0, 0, 'R', 1);
                    $pdf->Ln();
                }       
            }
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(170, 6, "TOTAL", 0, 0, 'C', 0);
            $pdf->Cell(20, 6, "$".number_format($suma_deposito_libro,2,".",","), "T", 0, 'R', 0);
            $pdf->Ln(8);
            $head=$pdf->GetY();
        }
        $pdf->SetY($head+5);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(63, 6, "Saldo en Libros", 0, 0, 'C', 0);
        $pdf->Cell(63, 6, "Saldo Conciliado", 0, 0, 'C', 0);
        $pdf->Cell(63, 6, "Saldo en Bancos", 0, 1, 'C', 0);
        $pdf->Cell(63, 6, number_format($datos[0]->saldo_libro,2,".",","), 0, 0, 'C', 0);
        $pdf->Cell(63, 6, number_format($datos[0]->nuevo_saldo,2,".",","), 0, 0, 'C', 0);
        $pdf->Cell(63, 6, number_format($datos[0]->saldo_banco,2,".",","), 0, 1, 'C', 0);
        $pdf->Ln(30);
        $pdf->Cell(30, 6, '', 0, 0, 'C', 0);
        $pdf->Cell(50, 6, 'CONTADOR', 'T', 0, 'C', 0);
        $pdf->Cell(30, 6, '', 0, 0, 'C', 0);
        $pdf->Cell(50, 6, 'GERENTE GENERAL', 'T', 1, 'C', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 6, '', 0, 0, 'C', 0);
        $pdf->Cell(50, 6, utf8_decode($empresa[0]->nombre_contador), 0, 0, 'C', 0);
        $pdf->Cell(30, 6, '', 0, 0, 'C', 0);
        $pdf->Cell(50, 6, utf8_decode($empresa[0]->nomb_representante), 0, 0, 'C', 0);
        $pdf->Ln();
        if($ruta==null){
            $pdf->Output("conciliacion_bancaria.pdf", "D");
        }else{
            $pdf->Output($ruta.'/'."conciliacion_bancaria.pdf", "F");
        }
        
    }  
}
