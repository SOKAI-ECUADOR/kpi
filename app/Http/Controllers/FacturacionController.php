<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\Guia_remision;
use App\Models\FacturaCompra;
use App\Models\LiquidacionCompra;
use App\Models\Notacredito;
use App\Models\Notadebito;
use App\Models\Retencion_Liquidacion_Compra;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;

//agrega las librerias de generador de xml y creacion de codigo de acceso
include 'class/lib/nusoap.php';

use nusoap_client;

include 'class/generarPDF.php';

use DOMDocument;
use generarPDF;

include_once getenv("FILE_CONFIG_PHP");

class FacturacionController extends Controller
{
    //la respuesta de factura al momento de recibir respuesta divide por tipo de factura, este módulo ingresa siempre, independiente de si envia o no la factura
    public function respfactura(Request $request)
    {
        //guarda la fecha actual del servidor
        $hoy = Carbon::now();
        //obtiene el tipo
        $tipo = $request->tipo;
        //verifica que tipo de factura es, editando la factura y agregando el mensaje enviado al sri, el estado del envio y la info adicional
        if ($tipo == 'factura_venta') {
            $fact = Factura::findOrFail($request->id);
            $fact->respuesta = $request->estado["estado"];
            $fact->mensaje_sri = $request->estado["mensaje"];
            $fact->informacion_sri = $request->estado["informacion"];
            $fact->fecha_factura = $hoy;
            $fact->save();
        } else if ($tipo == 'retencion_compra') {
            DB::update("UPDATE factura_compra SET respuesta = '" . $request->estado['estado'] . "', mensaje_sri = '" . $request->estado['mensaje'] . "', informacion_sri = '" . $request->estado['informacion'] . "', fecha_factura = '" . $hoy . "' WHERE id_factcompra = " . $request->id);
        } else if ($tipo == 'nota_credito_venta') {
            $fact = Notacredito::findOrFail($request->id);
            $fact->respuesta = $request->estado["estado"];
            $fact->mensaje_sri = $request->estado["mensaje"];
            $fact->informacion_sri = $request->estado["informacion"];
            $fact->fecha_factura = $hoy;
            $fact->save();
        } else if ($tipo == 'nota_debito_venta') {
            $fact = Notadebito::findOrFail($request->id);
            $fact->respuesta = $request->estado["estado"];
            $fact->mensaje_sri = $request->estado["mensaje"];
            $fact->informacion_sri = $request->estado["informacion"];
            $fact->fecha_factura = $hoy;
            $fact->save();
        } else if ($tipo == 'guia_remision_venta') {
            $fact = Guia_remision::findOrFail($request->id);
            $fact->respuesta = $request->estado["estado"];
            $fact->mensaje_sri = $request->estado["mensaje"];
            $fact->informacion_sri = $request->estado["informacion"];
            $fact->fecha_factura = $hoy;
            $fact->save();
        } else if ($tipo == 'guia_remision_nota_venta') {
            $fact = Guia_remision::findOrFail($request->id);
            $fact->respuesta = $request->estado["estado"];
            $fact->mensaje_sri = $request->estado["mensaje"];
            $fact->informacion_sri = $request->estado["informacion"];
            $fact->fecha_factura = $hoy;
            $fact->save();
        }else if ($tipo == 'retencion_liquidacion_compra'){
            DB::update("UPDATE retencion_liquidacion_compra SET 
            respuesta = '{$request->estado["estado"]}',
            mensaje_sri = '{$request->estado["mensaje"]}',
            informacion_sri = '{$request->estado["informacion"]}',
            fecha_autorizacion = '{$hoy}' 
            WHERE id_liquidacion_compra = " . $request->id);
        }else if ($tipo == 'liquidacion_compra'){
            $fact = LiquidacionCompra::findOrFail($request->id);
            $fact->respuesta = $request->estado["estado"];
            $fact->mensaje_sri = $request->estado["mensaje"];
            $fact->informacion_sri = $request->estado["informacion"];
            $fact->fecha_envio = $hoy;
            $fact->save();
        }
    }
    //lee el xml de la factura creada
    public function leerFactura(Request $request)
    {
        //en este módulo los valores del xml no permitidos se remplaza por valores permitidos
        //se usa str_replace(valores no permitidos, valores permitidos, la variable)
        session_start();
        $ruta = $request->factura;
        $myxmlfilecontent = file_get_contents($ruta);

        $text = trim(preg_replace('/\s+/', ' ', $myxmlfilecontent));
        $text = preg_replace("/(?<=\>)(\r?\n)|(\r?\n)(?=\<\/)/", '', $text);
        $text = trim(str_replace('> <', '><', $text));
        $text = utf8_encode($text);

        $xml = simplexml_load_string($text);
        $text = utf8_decode($text);
        if ($xml->attributes()->version) {
            $version = $xml->attributes()->version;
            $id = $xml->attributes()->id;
            //trim elimina los espacios en blanco y los borra
            // Agregar Encabezados
            $text = trim(preg_replace('/<factura version="' . $version . '" id="' . $id . '">/', '<factura id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<notaCredito version="' . $version . '" id="' . $id . '">/', '<notaCredito id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<notaDebito version="' . $version . '" id="' . $id . '">/', '<notaDebito id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<comprobanteRetencion version="' . $version . '" id="' . $id . '">/', '<comprobanteRetencion id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<guiaRemision version="' . $version . '" id="' . $id . '">/', '<guiaRemision id="' . $id . '" version="' . $version . '">', $text));
            $text = trim(preg_replace('/<liquidacionCompra version="' . $version . '" id="' . $id . '">/', '<liquidacionCompra id="' . $id . '" version="' . $version . '">', $text));

            $text = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'º'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', '°'),
                $text
            );
            $reg = strpos($text, 'RÉGIMEN');
            if ($reg == false) {
                $text = str_replace(
                    array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                    array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                    $text
                );
            } else {
                $text = str_replace(
                    array('é', 'è', 'ë', 'ê',  'È', 'Ê', 'Ë'),
                    array('e', 'e', 'e', 'e',  'E', 'E', 'E'),
                    $text
                );
            }
            $text = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $text
            );

            $text = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                $text
            );

            $text = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $text
            );

            $text = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C'),
                $text
            );
            $text = str_replace(array('(', ')', '{', '}', '®', '™', '°'), "", $text);
            if ($reg == false) {
                $no_permitidas = array(
                    "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã",
                    "ÃŠ", "ÃŽ", "Ã", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã", "Ã‹", "Ñ", "*", "%"
                );
                $permitidas =    array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "N", ".", ".");
            } else {
                $no_permitidas = array(
                    "á", "é", "í", "ó", "ú", "Á",  "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã",
                    "ÃŠ", "ÃŽ", "Ã", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã", "Ã‹", "Ñ", "*", "%"
                );
                $permitidas =    array("a", "e", "i", "o", "u", "A",  "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E", "N", ".", ".");
            }
            $text = str_replace($no_permitidas, $permitidas, $text);
        }
        echo $text;
    }
    //hexINt
    public function hexToInt($hex)
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }
    //firma del xml
    public function firmaphp(Request $request)
    {
        //recupera el xml con la firma del usuario y los permisos mediante la firma electrónica
        $mensaje = $request->mensaje;
        $claveAcceso = $request->claveAcceso;
        $file = fopen($request->carpeta . $claveAcceso . ".xml", "w");
        fwrite($file, $mensaje . PHP_EOL);
        fclose($file);
    }
    //validación de la estructura del comprobante xml si se encuentra bien formado
    public function validarComprobantephp(Request $request)
    {
        // crea try catch para obtener el error al momento de no poderse enviar
        try {
            header("Content-Type: text/plain");
            session_start();

            $service = $request->service;
            $claveAcceso = $request->claveAcceso;
            $carpeta = $request->carpeta;
            $id_empresa = $request->id_empresa;
            $carpeta_autorizados = $carpeta . "/respuestaSRI";
            $carpeta_errores = $carpeta . "/respuestaSRI";
            $carpeta_errores_comp = $carpeta . "/errores";

            if (!file_exists($carpeta_autorizados)) {
                mkdir($carpeta_autorizados, 0755, true);
            }
            if (!file_exists($carpeta_errores)) {
                mkdir($carpeta_errores, 0755, true);
            }
            if (!file_exists($carpeta_errores_comp)) {
                mkdir($carpeta_errores_comp, 0755, true);
            }
            $contenido = $carpeta . $claveAcceso . ".xml";
            $errorlog = $carpeta . "errores/log.txt";
            $errorfact = $carpeta . "errores/" . $claveAcceso . ".txt";

            $content = file_get_contents($contenido);
            $mensaje = base64_encode($content);
            //estas son las dos rutas de envio (pruebas y producción)
            $recupera = DB::select("SELECT * FROM empresa WHERE id_empresa=" . $id_empresa);
            if ($recupera[0]->ambiente == 2) {
                $servicio = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
            } else {
                $servicio = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";
            }
            //mediante el envio de nusoap que permite el envio de información en formato xml a la ruta de recepción
            $parametros = array();
            $parametros['xml'] = $mensaje;
            $client = new nusoap_client($servicio);
            $client->soap_defencoding = 'utf-8';
            $result = $client->call("validarComprobante", $parametros, "http://ec.gob.sri.ws.recepcion");
            $response = array();
            $file = fopen($errorlog, "a+");
            fwrite($file, "Servicio: " . $service . PHP_EOL);
            fwrite($file, "Clave Acceso: " . $claveAcceso . PHP_EOL);

            $_SESSION['validarComprobante'] = $result;
            //si el envio del cliente es erroneo manda error y crea un txt con el error
            if ($client->fault) {
                $file_error = fopen($errorfact, "w");
                fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                fwrite($file_error, "Respuesta: " . print_r($result, true) . PHP_EOL);
                fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                fclose($file_error);
                fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
                //echo serialize($result);
                $err_sri = [
                    "estado" => "Error",
                    "estado1" => "Error 3",
                    "mensaje" => "Error en el servicio del SRI",
                    "informacion" => "El SRI se encuentra fuera de servicio, intente mas tarde"
                ];
                $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
                $comprobante = $client->responseData;

                //usando libreria utf8 "cambio tildes" 
                $simplexml = simplexml_load_string(utf8_encode($comprobante));
                $dom = new DOMDocument('1.0');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                fwrite($file_comprobante, $xml . PHP_EOL);
                fclose($file_comprobante);
                //responde mediante un json response el resultado
                return response()->json($err_sri, 500);
            } else {
                //si existe error de envio mediante peticion entraría al siguiente error
                $error = $client->getError();
                if ($error) {
                    fwrite($file, "Respuesta: " . print_r($error, true) . PHP_EOL);
                    $file_error = fopen($errorfact, "w");
                    fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                    fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                    fwrite($file_error, "Respuesta: " . print_r($error, true) . PHP_EOL);
                    fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                    fclose($file_error);
                    //echo serialize($error);
                    $err_sri = [
                        "estado" => "Error",
                        "estado1" => "Error 4",
                        "mensaje" => "Error en el servicio del SRI",
                        "informacion" => "El SRI se encuentra fuera de servicio, intente mas tarde"
                    ];

                    $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
                    $comprobante = $client->responseData;

                    //usando libreria utf8 "cambio tildes" 
                    $simplexml = simplexml_load_string(utf8_encode($comprobante));
                    $dom = new DOMDocument('1.0');
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                    fwrite($file_comprobante, $xml . PHP_EOL);
                    fclose($file_comprobante);
                    //responde mediante un json response el resultado
                    return response()->json($err_sri, 500);
                } else {
                    //si el envio fue correcta envia un resultado mediante serialize
                    if ($result['estado'] == 'RECIBIDA') {
                        fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
                        echo serialize($result);
                        $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
                        $comprobante = $client->responseData;

                        //usando libreria utf8 "cambio tildes" 
                        $simplexml = simplexml_load_string(utf8_encode($comprobante));
                        $dom = new DOMDocument('1.0');
                        $dom->preserveWhiteSpace = false;
                        $dom->formatOutput = true;
                        $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                        fwrite($file_comprobante, $xml . PHP_EOL);
                        fclose($file_comprobante);
                        //si el envio fue diferente de RECIBIDA enviara el error de envio y el mensaje del mismo
                    } else {
                        fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
                        $file_error = fopen($errorfact, "w");
                        fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                        fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                        fwrite($file_error, "Respuesta: " . print_r($result, true) . PHP_EOL);
                        fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                        fclose($file_error);
                        //si el error es que se envia la misma factura envia CLAVE ACCESO REGISTRADA
                        if (utf8_encode($result['comprobantes']['comprobante']['mensajes']['mensaje']["mensaje"]) == "CLAVE ACCESO REGISTRADA") {
                            fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
                            echo serialize($result);
                            //caso contrario envia el nombre
                        } else {
                            $infoadicional = "Intente enviarlo mas tarde";
                            if (isset($result['comprobantes']['comprobante']['mensajes']['mensaje']["informacionAdicional"])) {
                                $infoadicional = utf8_encode($result['comprobantes']['comprobante']['mensajes']['mensaje']["informacionAdicional"]);
                            }
                            $err_sri = [
                                "estado" => $result['estado'],
                                "estado1" => "Error 5",
                                "mensaje" => utf8_encode($result['comprobantes']['comprobante']['mensajes']['mensaje']["mensaje"]),
                                "informacion" => $infoadicional
                            ];
                            //responde mediante un json response el resultado
                            return response()->json($err_sri, 500);
                        }
                        $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
                        $comprobante = $client->responseData;

                        //usando libreria utf8 "cambio tildes" 
                        $simplexml = simplexml_load_string(utf8_encode($comprobante));
                        $dom = new DOMDocument('1.0');
                        $dom->preserveWhiteSpace = false;
                        $dom->formatOutput = true;
                        $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                        fwrite($file_comprobante, $xml . PHP_EOL);
                        fclose($file_comprobante);
                    }
                }
            }
            fwrite($file, "\n__________________________________________________________________\n" . PHP_EOL);
            fclose($file);
        } catch (\Throwable $th) {
            $claveAcceso = $request->claveAcceso;
            $carpeta = $request->carpeta;
            $carpeta_errores = $carpeta . "/respuestaSRI";
            if (!file_exists($carpeta_errores)) {
                mkdir($carpeta_errores, 0755, true);
            }
            // si el envio no se efectuo entraria a esta instancia de error
            $err_sri = [
                "estado" => "Error",
                "estado1" => "Error validar",
                "mensaje" => "Error en el servicio del SRI",
                "informacion" => "El SRI se encuentra fuera de servicio, intente mas tarde"
            ];
            $carpeta = $request->carpeta;
            $claveAcceso = $request->claveAcceso;
            $carpeta_errores = $carpeta . "/respuestaSRI";

            if (!file_exists($carpeta_errores)) {
                mkdir($carpeta_errores, 0755, true);
            }

            // $client = new nusoap_client($servicio);
            // $client->soap_defencoding = 'utf-8';
            // $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
            // $comprobante = $client->responseData;

            // //usando libreria utf8 "cambio tildes" 
            // $simplexml = simplexml_load_string(utf8_encode($comprobante));
            // $dom = new DOMDocument('1.0');
            // $dom->preserveWhiteSpace = false;
            // $dom->formatOutput = true;
            // $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

            // fwrite($file_comprobante, $xml . PHP_EOL);
            // fclose($file_comprobante);
            $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
            fwrite($file_comprobante, "Error en el servicio del SRI: ".$th);
            fclose($file_comprobante);
            //responde mediante un json response el resultado
            return response()->json($err_sri, 500);
        }
    }
    //envio de la estructuración
    public function autorizacionComprobantephp(Request $request)
    {
        //recupera variables de la factura
        session_start();
        
        $claveAcceso = $request->claveAcceso;
        $service = $request->service;
        $id_empresa = $request->usuario["id_empresa"];
        $carpeta = $request->carpeta;
        $fecha = $request->fecha;
        $valor = $request->valor;
        $logo = $request->logo;
        $nombre_empresa = $request->nombre_empresa;
        $id_factura = $request->id_factura;

        $validado = $carpeta . $claveAcceso . ".xml";
        $errorlog = $carpeta . "/errores/log.txt";
        $carpeta_autorizados = $carpeta . "/respuestaSRI";
        $carpeta_errores = $carpeta . "/respuestaSRI";

        if (!file_exists($carpeta_autorizados)) {
            mkdir($carpeta_autorizados, 0755, true);
        }
        if (!file_exists($carpeta_errores)) {
            mkdir($carpeta_errores, 0755, true);
        }
        $errorfact = $carpeta . '/errores/' . $claveAcceso . ".txt";

        $recupera = DB::select("SELECT * FROM empresa WHERE id_empresa=" . $id_empresa);
        $imagen = $recupera[0]->logo;
        $empresas = $recupera[0];
        //estas son las dos rutas de envio (pruebas y producción)
        if ($recupera[0]->ambiente == 2) {
            $servicio = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        } else {
            $servicio = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";
        }

        //mediante el envio de nusoap que permite el envio de información en formato xml a la ruta de recepción
        $parametros = array();
        $parametros['claveAccesoComprobante'] = $claveAcceso;
        $client = new nusoap_client($servicio);
        $error = $client->getError();
        $client->soap_defencoding = 'utf-8';
        //envia el comprobante con la estructuración al sri principal
        $result = $client->call("autorizacionComprobante", $parametros, "http://ec.gob.sri.ws.autorizacion");
        $_SESSION['autorizacionComprobante'] = $result;
        $response = array();
        $file = fopen($errorlog, "a+");
        fwrite($file, "Servicio: " . $service . PHP_EOL);
        fwrite($file, "Clave Acceso: " . $claveAcceso . PHP_EOL);

        //si el envio del cliente es erroneo manda error y crea un txt con el error
        if ($client->fault) {
            fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
            $file_error = fopen($errorfact, "w");
            fwrite($file_error, "Servicio: " . $service . PHP_EOL);
            fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
            fwrite($file_error, "Respuesta: " . print_r($result, true) . PHP_EOL);
            fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
            fclose($file_error);

            $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
            $comprobante = $client->responseData;

            //usando libreria utf8 "cambio tildes" 
            $simplexml = simplexml_load_string(utf8_encode($comprobante));
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

            fwrite($file_comprobante, $xml . PHP_EOL);
            fclose($file_comprobante);

            $err_sri = [
                "estado" => "Error",
                "estado1" => "Error 6",
                "mensaje" => "Error en el servicio del SRI",
                "informacion" => "El SRI se encuentra fuera de servicio, intente mas tarde"
            ];
            //responde mediante un json response el resultado
            return response()->json($err_sri, 500);
        } else {
            //si existe error de envio mediante peticion entraría al siguiente error
            $error = $client->getError();
            if ($error) {
                fwrite($file, "Respuesta: " . print_r($error, true) . PHP_EOL);
                $file_error = fopen($errorfact, "w");
                fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                fwrite($file_error, "Respuesta: " . print_r($error, true) . PHP_EOL);
                fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                fclose($file_error);
                //echo serialize($error);

                $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
                $comprobante = $client->responseData;

                //usando libreria utf8 "cambio tildes" 
                $simplexml = simplexml_load_string(utf8_encode($comprobante));
                $dom = new DOMDocument('1.0');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                fwrite($file_comprobante, $xml . PHP_EOL);
                fclose($file_comprobante);

                $err_sri = [
                    "estado" => "Error",
                    "estado1" => "Error 7",
                    "mensaje" => "Error en el servicio del SRI",
                    "informacion" => "El SRI se encuentra fuera de servicio, intente mas tarde"
                ];
                //responde mediante un json response el resultado
                return response()->json($err_sri, 500);
            } else {
                fwrite($file, "Respuesta: " . print_r($result, true) . PHP_EOL);
                // si el estado del sri mantiene un error diferente de un valor valido ingresa al siguiente error
                if ($result['autorizaciones']['autorizacion']['estado'] != 'NO AUTORIZADO' && $result['autorizaciones']['autorizacion']['estado'] != 'AUTORIZADO') {
                    echo serialize($result);
                    $file_error = fopen($errorfact, "w");
                    fwrite($file_error, "Servicio: " . $service . PHP_EOL);
                    fwrite($file_error, "Clave Acceso: " . $claveAcceso . PHP_EOL);
                    fwrite($file_error, "Respuesta: " . print_r($result, true) . PHP_EOL);
                    fwrite($file_error, "\n__________________________________________________________________\n" . PHP_EOL);
                    fclose($file_error);

                    $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
                    $comprobante = $client->responseData;

                    //usando libreria utf8 "cambio tildes" 
                    $simplexml = simplexml_load_string(utf8_encode($comprobante));
                    $dom = new DOMDocument('1.0');
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                    fwrite($file_comprobante, $xml . PHP_EOL);
                    fclose($file_comprobante);

                    $infoadicional = "Intente enviar el comporbante mas tarde";
                    if (isset($result['autorizaciones']['autorizacion']['mensajes']['mensaje']['informacionAdicional'])) {
                        $infoadicional = utf8_encode($result['autorizaciones']['autorizacion']['mensajes']['mensaje']['informacionAdicional']);
                    }
                    $err_sri = [
                        "estado" => $result['autorizaciones']['autorizacion']['estado'],
                        "estado1" => "Error 8",
                        "mensaje" => utf8_encode($result['autorizaciones']['autorizacion']['mensajes']['mensaje']['mensaje']),
                        "informacion" => $infoadicional
                    ];
                    //responde mediante un json response el resultado
                    return response()->json($err_sri, 500);
                } else {
                    //si el estado es diferente de NO AUTORIZADO (osea autorizado)
                    if ($result['autorizaciones']['autorizacion']['estado'] != 'NO AUTORIZADO') {
                        if (!empty($result['autorizaciones']['autorizacion']['comprobante'])) {
                            $file_comprobante = fopen($carpeta_autorizados . "/" . $claveAcceso . ".xml", "w");
                            $comprobante = $client->responseData;
                            $simplexml = simplexml_load_string(utf8_encode($comprobante));
                            $dom = new DOMDocument('1.0');
                            $dom->preserveWhiteSpace = false;
                            $dom->formatOutput = true;
                            $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                            fwrite($file_comprobante, $xml . PHP_EOL);
                            fclose($file_comprobante);
                            $dataComprobante = simplexml_load_string(utf8_encode($result['autorizaciones']['autorizacion']['comprobante']));
                            if ($dataComprobante->infoFactura) {
                                //
                                //envio a la factura de venta pdf
                                self::factura_venta_pdf($id_factura, "v");
                                $correo = "";
                                foreach ($dataComprobante->infoAdicional->campoAdicional as $a) {
                                    foreach ($a->attributes() as $b) {
                                        if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                                            $correo = $a;
                                        }
                                    }
                                }
                                echo "LLega aqui autorizacion";
                                //envio de correo
                                self::enviarCorreo('Factura', $dataComprobante->infoCompRetencion->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);

                                echo "LLega aqui envio autorizacion";
                            }
                            if ($dataComprobante->infoNotaCredito) {
                                $facturaPDF = new generarPDF();
                                $facturaPDF->notaCreditoPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa);
                            }
                            if ($dataComprobante->infoCompRetencion) {
                                if($request->tipo == 'retencion_liquidacion_compra'){
                                    self::retencion_compra_pdf($id_factura, "v","lc");
                                    $correo = "";
                                    foreach ($dataComprobante->infoAdicional->campoAdicional as $a) {
                                        foreach ($a->attributes() as $b) {
                                            if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                                                $correo = $a;
                                            }
                                        }
                                    }
                                    self::enviarCorreo('retencion_liquidacion_compra', $dataComprobante->infoCompRetencion->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
                                }else{
                                    self::retencion_compra_pdf($id_factura, "v","fc");
                                    $correo = "";
                                    foreach ($dataComprobante->infoAdicional->campoAdicional as $a) {
                                        foreach ($a->attributes() as $b) {
                                            if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                                                $correo = $a;
                                            }
                                        }
                                    }
                                    self::enviarCorreo('retencion_compra', $dataComprobante->infoCompRetencion->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
                                }
                                
                            }
                            if ($dataComprobante->infoGuiaRemision) {
                                if ($request->tipo == 'guia_remision_nota_venta') {
                                    self::guiapdf($id_factura, "v", "nv");
                                    $correo = "";
                                    foreach ($dataComprobante->infoAdicional->campoAdicional as $a) {
                                        foreach ($a->attributes() as $b) {
                                            if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                                                $correo = $a;
                                            }
                                        }
                                    }
                                    self::enviarCorreo('guia_remision_nota_venta', $dataComprobante->infoCompRetencion->razonSocialTransportista, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
                                } else {
                                    self::guiapdf($id_factura, "v", "fv");
                                    $correo = "";
                                    foreach ($dataComprobante->infoAdicional->campoAdicional as $a) {
                                        foreach ($a->attributes() as $b) {
                                            if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                                                $correo = $a;
                                            }
                                        }
                                    }
                                    self::enviarCorreo('Factura', $dataComprobante->infoCompRetencion->razonSocialTransportista, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
                                }
                            }
                            if ($dataComprobante->infoNotaDebito) {
                                $facturaPDF = new generarPDF();
                                $facturaPDF->notaDebitoPDF($dataComprobante, $claveAcceso, $id_empresa, $imagen, $empresas, $fecha, $valor, $logo, $nombre_empresa);
                            }
                            if ($dataComprobante->infoLiquidacionCompra) {
                                self::liquidacion_compra_pdf($id_factura, "v");
                                $correo = "";
                                foreach ($dataComprobante->infoAdicional->campoAdicional as $a) {
                                    foreach ($a->attributes() as $b) {
                                        if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                                            $correo = $a;
                                        }
                                    }
                                }
                                self::enviarCorreo('LiquidacionCompra', $dataComprobante->infoCompRetencion->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa);
                            }
                            echo serialize($result);
                        }
                    } else {
                        $infoadicional = "Intente enviar mas tarde";
                        if (isset($result['autorizaciones']['autorizacion']['mensajes']['mensaje']['informacionAdicional'])) {
                            $infoadicional = utf8_encode($result['autorizaciones']['autorizacion']['mensajes']['mensaje']['informacionAdicional']);
                        }

                        $file_comprobante = fopen($carpeta_errores . '/' . $claveAcceso . ".xml", "w");
                        $comprobante = $client->responseData;

                        //usando libreria utf8 "cambio tildes" 
                        $simplexml = simplexml_load_string(utf8_encode($comprobante));
                        $dom = new DOMDocument('1.0');
                        $dom->preserveWhiteSpace = false;
                        $dom->formatOutput = true;
                        $xml = str_replace(['&lt;', '&gt;'], ['<', '>'], $comprobante);

                        fwrite($file_comprobante, $xml . PHP_EOL);
                        fclose($file_comprobante);

                        $err_sri = [
                            "estado" => $result['autorizaciones']['autorizacion']['estado'],
                            "estado1" => "Error 9",
                            "mensaje" => utf8_encode($result['autorizaciones']['autorizacion']['mensajes']['mensaje']['mensaje']),
                            "informacion" => $infoadicional
                        ];
                        //responde mediante un json response el resultado
                        return response()->json($err_sri, 500);
                    }
                }
            }
        }
        fwrite($file, "\n__________________________________________________________________\n" . PHP_EOL);
        fclose($file);
    }

    //cracion de envio de pdf y correo
    public function crearPdf(Request $request)
    {
        //estructuración de creación de pdf de prueba
        $facturaPDF = new generarPDF();
        $variable = (array) $request->e;
        $carpetanombre = constant("DATA_EMPRESA") . $request->id_empresa . "/comprobantes/factura/";
        $carpeta1 = $carpetanombre . $request->claveAcceso . ".xml";
        $dataComprobante = simplexml_load_file($carpeta1);
        //envia a facturaPDF controlador a la funcion de facturaPDF_prueba
        $facturaPDF->facturaPDF_prueba($dataComprobante, $request->claveAcceso, $request->id_empresa, $request->imagen);
    }

    //envio del correo  de las facturas
    public function enviarCorreo($tipo, $nombre, $claveAcceso, $email, $id_empresa, $empresas, $fecha, $valor, $logo, $nombre_empresa)
    {
        //return "LLega al email";
        try {
            $dominio = env('APP_URL', '');
            //echo($dominio);
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
            //$mail->addAddress($email);
            echo($email);
            echo(gettype($email));
            $email = explode(" ", $email);
            
            // for ($i = 0; $i < count($email); $i++) {
            //     $mail->addAddress($email[$i]);
            // }
            for ($i = 0; $i < count($email); $i++) {
                if(strpos($email[$i],";")!==false){
                    $email_2=explode(";",$email[$i]);
                    for ($j = 0; $j < count($email_2); $j++) {
                        $mail->addAddress($email_2[$j]);
                    }
                }else{
                    $mail->addAddress($email[$i]);
                }
            }
            $razon_social = DB::select("SELECT * from empresa where id_empresa={$id_empresa}");

            //$mail->addAddress("wily2809@hotmail.com");
            $mail->isHTML(true);
            $pdflink ="";
            echo($tipo);
            //verifica que tipo de factura es y recupera los archivos que se requiere
            if($tipo == 'Factura'){
                $factura_id=DB::select("SELECT * from factura where id_empresa={$id_empresa} and clave_acceso like '%$claveAcceso%'");
                $pdflink =  $dominio . '/api/creacion_factura_venta_pdf/' . $factura_id[0]->id_factura . '/v';
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.pdf');
                $carpeta_respuesta_sri = constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/respuestaSRI/' . $claveAcceso . '.xml';
                if (file_exists($carpeta_respuesta_sri)) {
                    $mail->addAttachment($carpeta_respuesta_sri);
                } else {
                    $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/' . $claveAcceso . '.xml');
                }
            } else if ($tipo == 'Notacredito') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.xml');
            } else if ($tipo == 'Notadebito') {
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.pdf');
                $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notadebito/' . $claveAcceso . '.xml');
            } else if ($tipo == 'retencion_compra') {
                if(file_exists(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $claveAcceso . '.pdf')){
                    
                    $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/' . $claveAcceso . '.pdf');
                }else{
                    $mail->addAttachment(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/retencioncompra/retencion_' . $claveAcceso . '.pdf');
                }
                $retencion_id=DB::select("SELECT * from factura_compra where id_empresa={$id_empresa} and observacion like '%$claveAcceso%'");
                $pdflink =  $dominio . '/api/creacion_retencion_compra_pdf/' . $retencion_id[0]->id_factcompra . '/v/fc';
                
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
            } else if ($tipo == 'guia_remision_nota_venta') {
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
            
            //recupera el logotipo de la empresa si existe
            if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo)) {
                $mail->AddEmbeddedImage(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $logo, 'logocliente');
            } else {
                $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logocliente');
            }
            //estructuración de estilo de la factura como body
            $mail->AddEmbeddedImage('images/logo/logo-correo.png', 'logo1');
            // if($tipo == 'Notacreditocompra'){
            //     $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Nota Credito Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
            // }else{
            //     if($tipo == 'LiquidacionCompra'){
            //         $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Liquidacion Compra&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
            //     }else{
            //         $bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">' . strtoupper(utf8_decode($nombre_empresa)) . '</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Factura&nbsp; ' . substr($claveAcceso, 24, 3) . '-' . substr($claveAcceso, 27, 3) . '-' . substr($claveAcceso, 30, 9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';                                          
            //     }
                
            //    //$bodyContent = '<div style="background-color:#ECF0F1"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#FFFFFF;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-size:22px;font-weight:bold;">'. strtoupper(utf8_decode($nombre_empresa)) .'</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#000000;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Estimado Cliente</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#FF0101;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Factura&nbsp; ' . substr($claveAcceso,24,3) .'-'. substr($claveAcceso,27,3).'-'.substr($claveAcceso,30,9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Valor:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#000000;font-weight:bold;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #FF0101"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#000000;font-weight:600;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#000000;font-weight:600;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /><span style="color:#000000;font-weight:600;"><a href="https://sokai.com.ec/">WWW.SOKAI.COM.EC</a><br> 0963369209 - 0979092243</span></td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
            //     //$bodyContent = '<div style="background-color:#10163A"> <table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td valign="top" align="left"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#050b19;padding: 50px 0;"> <tr> <td align="center" width="100%"> <!--[if gte mso 9]><table width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table class="width600 main-container" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px"> <tr> <td width="100%"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top"> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:25px;padding-left:10px"> <div style="font-family:arial;font-size:30px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">'. strtoupper(utf8_decode($nombre_empresa)) .'</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logocliente" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Fecha de emisi&oacute;n: ' . $fecha . ' </span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0;padding-bottom: 5px; margin: 0;text-align: center;"><span style="color:#ffffff;font-size:22px;">Estimado &nbsp; ' . $nombre . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ffffff;">Le informamos que su comprobante electr&oacute;nico ha sido emitido exitosamente y se encuentra adjunto al presente correo.</span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:20px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Factura&nbsp; ' . substr($claveAcceso,24,3) .'-'. substr($claveAcceso,27,3).'-'.substr($claveAcceso,30,9) . ' </span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Valor de la factura:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:48px;"><span style="color:#ecf0f1;">&#36; ' . $valor . ' </span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Si desea visualizar el comprobante en linea da clic aqui:</span></p> <p style="padding: 0; margin: 0;text-align: center;">&nbsp;</p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center" style="padding:20px"> <!--[if !mso]><!-- --> <a href="" target="_blank" style="display:inline-block; text-decoration:none;" class="fluid-on-mobile"> <span> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </td> </tr> </table> </span> </a> <!--<![endif]--> <div style="display:none; mso-hide: none;"> <table cellpadding="0" cellspacing="0" border="0" bgcolor="#0094c5" style="border-radius:5px;border-collapse:separate !important;background-color:#0094c5" class="fluid-on-mobile"> <tr> <td align="center" style="padding:15px"> <a href="" target="_blank" style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;text-decoration:none;text-align:center;"> <span style="color:#ffffff !important;font-family:Arial;font-size:18px;mso-line-height:exactly;line-height:25px;mso-text-raise:3px;"> <font style="color:#ffffff;" class="button"> <span>Visualizar Factura</span> </font> </span> </a> </td> </tr> </table> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:2px solid #a9a9a9"> <tr> <td> </td> </tr> </table> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" style="padding-top:5px;padding-right:10px;padding-bottom:5px;padding-left:10px"> <div style="font-family:arial;font-size:18px;color:#131313;line-height:25px;text-align:left"> <p style="padding: 0; margin: 0;text-align: center;"><span style="color:#ecf0f1;">Generado por SOKAI</span></p> <p style="padding: 0; margin: 0;text-align: center;"><span style="font-size:12px;"><span style="color:#ecf0f1;">Este mensaje es generado autom&aacute;ticamente</span></span></p> </div> </td> </tr> </table> <table cellpadding="0" cellspacing="0" border="0" width="100%"> <tr> <td valign="top" align="center"> <!--[if gte mso 9]><table width="300" cellpadding="0" cellspacing="0"><tr><td><![endif]--> <table cellpadding="0" cellspacing="0" border="0" width="300" style="max-width:100%" class="fluid-on-mobile img-wrap"> <tr> <td valign="top" align="center"><img src="cid:logo1" width="300" height="105" alt="" border="0" style="display:block;font-size:14px;max-width:100%;height:auto;" class="width300" /> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> <!--[if gte mso 9]></td></tr></table><![endif]--> </td> </tr> </table> </td> </tr> </table> </td> </tr> </table> </div>';
            // }
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
            //envia el correo con el tipo, de quien es y a donde va
            $final = ucfirst($tipo);
            $mail->Subject = utf8_decode($final . ' ' . $razon_social[0]->razon_social);
            $mail->Body = $bodyContent;
            //verifica si envio correctamente el correo
            if (!$mail->send()) {
                //Si el correo no fue enviado manda el siguiente error
                $err_sri = [
                    "estado" => "Enviado",
                    "estado1" => "Error 11",
                    "mensaje" => "El correo electrónico no se pudo enviar intente mas tarde",
                    "informacion" => "Verifique si los datos del correo de envio como del cliente son correctos"
                ];

                echo "ERROR EN EL CORREO";
                return response()->json($err_sri, 500);
            } else {
                //si el correo fue enviado exitosamente
                echo "SE REALIZO EL CORREO";
                return true;
            }
        } catch (\Throwable $th) {
            //si existe algun error en el envio del correo manda el siguiente error


            //Si el correo no fue enviado manda el siguiente error

            echo $th;
            echo "ERROR EN EL CORREO TRY CATCH";
            $err_sri = [
                "estado" => "Enviado",
                "estado1" => "Error 12",
                "mensaje" => "El correo electrónico no se pudo enviar intente mas tarde",
                "informacion" => "Verifique si los datos del correo de envio como del cliente son correctos"
            ];
            return response()->json($err_sri, 500);
        }
    }

    //Creación de la factura de venta en pdf
    public function factura_venta_pdf($id, $tipo)
    {
        //Selecciona la factura que va enviar
        $facturas = DB::select("SELECT *, (SELECT clave_acceso from guia_remision WHERE id_factura = factura.id_factura) as clave_acceso_guia FROM factura WHERE id_factura = $id");
        $id_cliente = $facturas[0]->id_cliente;
        $id_empresa = $facturas[0]->id_empresa;
        $id_punto_emision = $facturas[0]->id_punto_emision;
        $id_establecimiento = $facturas[0]->id_establecimiento;
        $clave_acceso = $facturas[0]->clave_acceso;

        //selecciona los clientes, empresa, los productos, pagos y clientes para crear en pdf
        $clientes = DB::select("SELECT * FROM cliente WHERE id_cliente = $id_cliente");
        $empresas = DB::select("SELECT em.*, es.urlweb,es.id_establecimiento FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
        $detalles = DB::select("SELECT det.*, pr.cod_principal,pr.cod_alterno,pr.codigo_barras, pr.total_ice as total_ice_pr,pr.descripcion as descripcion_producto FROM detalle det INNER JOIN producto pr ON det.id_producto=pr.id_producto WHERE det.id_factura = $id");
        $pagos = DB::select("SELECT fp.*, fps.descripcion, fpag.descripcion AS descripcionfp FROM factura_pagos fp LEFT JOIN forma_pagos fpag ON fp.id_forma_pagos=fpag.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri=fpag.id_forma_pagos_sri WHERE fp.id_factura=$id");
        $cliente = $clientes[0];
        $empresa = $empresas[0];
        $factura = $facturas[0];
        if($empresa->nombre_empresa=='TECHCOMP SOLUTIONS'){
            $pdf = \PDF::loadView('pdf/factura_tech', compact("factura", "cliente", "empresa", "detalles", "clave_acceso", "pagos"));
            //si la url tiene d va a descargar caso contrario solo va a ser una vista
            if ($tipo == 'd') {
                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura/$clave_acceso.pdf")->download("$clave_acceso.pdf");
            } else {
                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura/$clave_acceso.pdf")->stream("$clave_acceso.pdf");
            }
        }else{
            if($empresa->nombre_empresa=='GRUPO SOLIS INGENIERIA ESPECIALIZADA'){
                //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact
                $pdf = \PDF::loadView('pdf/factura_gruposolis', compact("factura", "cliente", "empresa", "detalles", "clave_acceso", "pagos"));
                //si la url tiene d va a descargar caso contrario solo va a ser una vista
                if ($tipo == 'd') {
                    return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura/$clave_acceso.pdf")->download("$clave_acceso.pdf");
                } else {
                    return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura/$clave_acceso.pdf")->stream("$clave_acceso.pdf");
                }
            }else{
                //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact
                $pdf = \PDF::loadView('pdf/factura_venta', compact("factura", "cliente", "empresa", "detalles", "clave_acceso", "pagos"));
                //si la url tiene d va a descargar caso contrario solo va a ser una vista
                if ($tipo == 'd') {
                    return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura/$clave_acceso.pdf")->download("$clave_acceso.pdf");
                } else {
                    return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/factura/$clave_acceso.pdf")->stream("$clave_acceso.pdf");
                }
            }
            
        }
        
    }
    public function nota_venta_pdf($id, $tipo)
    {
        //Selecciona la factura que va enviar
        $facturas = DB::select("SELECT *, (SELECT clave_acceso from guia_remision_nota_venta WHERE id_nota_venta = nota_venta.id_nota_venta) as clave_acceso_guia FROM nota_venta WHERE id_nota_venta = $id");
        $id_cliente = $facturas[0]->id_cliente;
        $id_empresa = $facturas[0]->id_empresa;
        $id_punto_emision = $facturas[0]->id_punto_emision;
        $id_establecimiento = $facturas[0]->id_establecimiento;
        $clave_acceso = $facturas[0]->clave_acceso;

        //selecciona los clientes, empresa, los productos, pagos y clientes para crear en pdf
        $clientes = DB::select("SELECT * FROM cliente WHERE id_cliente = $id_cliente");
        $empresas = DB::select("SELECT em.*, es.urlweb FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
        $detalles = DB::select("SELECT det.*, pr.cod_principal,pr.cod_alterno, pr.total_ice as total_ice_pr FROM detalle_nota_venta det INNER JOIN producto pr ON det.id_producto=pr.id_producto WHERE det.id_nota_venta = $id");
        $pagos = DB::select("SELECT fp.*, fps.descripcion, fpag.descripcion AS descripcionfp FROM nota_venta_pagos fp LEFT JOIN forma_pagos fpag ON fp.id_forma_pagos=fpag.id_forma_pagos LEFT JOIN forma_pagos_sri fps ON fps.id_forma_pagos_sri=fpag.id_forma_pagos_sri WHERE fp.id_nota_venta=$id");
        $cliente = $clientes[0];
        $empresa = $empresas[0];
        $factura = $facturas[0];
        //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact
        $pdf = \PDF::loadView('pdf/nota_venta', compact("factura", "cliente", "empresa", "detalles", "clave_acceso", "pagos"));
        $carpeta2 = constant("DATA_EMPRESA") . "$id_empresa/comprobantes/notaVenta";
        if (!file_exists($carpeta2)) {
            mkdir($carpeta2, 0755, true);
        }
        //si la url tiene d va a descargar caso contrario solo va a ser una vista
        if ($tipo == 'd') {
            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/notaVenta/$clave_acceso.pdf")->download("$clave_acceso.pdf");
        } else {
            return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/notaVenta/$clave_acceso.pdf")->stream("$clave_acceso.pdf");
        }
        //envia a la vista de factura_venta los datos almacenados en las variables  mdiante compact

    }
    public function retencion_compra_pdf($id, $tipo,$doc)
    {
        try{
            if($doc=='fc'){
                    $facturas = DB::select("SELECT * FROM factura_compra WHERE id_factcompra = $id");
                    $id_proveedor = $facturas[0]->id_proveedor;
                    $id_empresa = $facturas[0]->id_empresa;
                    $id_punto_emision = $facturas[0]->id_punto_emision;
                    $id_establecimiento = $facturas[0]->id_establecimiento;
                    $clave_acceso = $facturas[0]->observacion;

                    //proveedores, empresas, retenciones recupera
                    $proveedores = DB::select("SELECT * FROM proveedor WHERE id_proveedor = $id_proveedor");
                    $empresas = DB::select("SELECT em.*, es.urlweb FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
                    $retenciones = DB::select("SELECT retencion_factura_comp.*, YEAR(retencion_factura_comp.fcrea) as anio, MONTH(retencion_factura_comp.fcrea) as mes, DAY(retencion_factura_comp.fcrea) as dia,factura_compra.descripcion,
                    (SELECT IF(im.cod_imp, im.cod_imp, re.cod_retencion) FROM retencion re LEFT JOIN impuesto im ON im.id_imp = re.id_impuesto WHERE id_retencion = retencion_factura_comp.id_retencion_renta) as cod_retencion_renta,
                    (SELECT IF(im.cod_imp, im.cod_imp, re.cod_retencion) FROM retencion re LEFT JOIN impuesto im ON im.id_imp = re.id_impuesto WHERE id_retencion = retencion_factura_comp.id_retencion_iva) as cod_retencion_iva
                    FROM retencion_factura_comp
                    INNER JOIN factura_compra
                    on factura_compra.id_factcompra=retencion_factura_comp.id_factura
                    WHERE id_factura = $id
                    ");
                    $proveedor = $proveedores[0];
                    $empresa = $empresas[0];
                    $factura = $facturas[0];
                    //envia a la vista de retencion_compra los datos almacenados en las variables  mdiante compact
                    $pdf = \PDF::loadView('pdf/retencion_compra', compact("factura", "proveedor", "empresa", "clave_acceso", "retenciones"));
                    //si la url tiene d va a descargar caso contrario solo va a ser una vista
                    if ($tipo == 'd') {
                        return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/retencioncompra/retencion_$clave_acceso.pdf")->download("retencion_$clave_acceso.pdf");
                    } else {
                        return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/retencioncompra/retencion_$clave_acceso.pdf")->stream("retencion_$clave_acceso.pdf");
                    }
            }else{
                    $facturas = DB::select("SELECT *,fecha_emision as fech_emision FROM liquidacion_compra WHERE id_liquidacion_compra = $id");
                    $id_proveedor = $facturas[0]->id_proveedor;
                    $id_empresa = $facturas[0]->id_empresa;
                    $id_punto_emision = $facturas[0]->id_punto_emision;
                    $id_establecimiento = $facturas[0]->id_establecimiento;
                    $clave_acceso = $facturas[0]->observacion;

                    //proveedores, empresas, retenciones recupera
                    $proveedores = DB::select("SELECT * FROM proveedor WHERE id_proveedor = $id_proveedor");
                    $empresas = DB::select("SELECT em.*, es.urlweb FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
                    $retenciones = DB::select("SELECT retencion_liquidacion_compra.*, YEAR(retencion_liquidacion_compra.fcrea) as anio, MONTH(retencion_liquidacion_compra.fcrea) as mes, DAY(retencion_liquidacion_compra.fcrea) as dia,liquidacion_compra.descripcion,
                    (SELECT IF(im.cod_imp, im.cod_imp, re.cod_retencion) FROM retencion re LEFT JOIN impuesto im ON im.id_imp = re.id_impuesto WHERE id_retencion = retencion_liquidacion_compra.id_retencion_renta) as cod_retencion_renta,
                    (SELECT IF(im.cod_imp, im.cod_imp, re.cod_retencion) FROM retencion re LEFT JOIN impuesto im ON im.id_imp = re.id_impuesto WHERE id_retencion = retencion_liquidacion_compra.id_retencion_iva) as cod_retencion_iva
                    FROM retencion_liquidacion_compra
                    INNER JOIN liquidacion_compra
                    on liquidacion_compra.id_liquidacion_compra=retencion_liquidacion_compra.id_liquidacion_compra
                    WHERE retencion_liquidacion_compra.id_liquidacion_compra = $id
                    ");
                    $proveedor = $proveedores[0];
                    $empresa = $empresas[0];
                    $factura = $facturas[0];
                    $carpeta2 = constant("DATA_EMPRESA") . "$id_empresa/comprobantes/retencioncompra/liquidacion_compra";
                    if (!file_exists($carpeta2)) {
                        mkdir($carpeta2, 0755, true);
                    }
                    //envia a la vista de retencion_compra los datos almacenados en las variables  mdiante compact
                    $pdf = \PDF::loadView('pdf/retencion_compra', compact("factura", "proveedor", "empresa", "clave_acceso", "retenciones"));
                    //si la url tiene d va a descargar caso contrario solo va a ser una vista
                    if ($tipo == 'd') {
                        return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/retencioncompra/liquidacion_compra/retencion_$clave_acceso.pdf")->download("retencion_$clave_acceso.pdf");
                    } else {
                        return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/retencioncompra/liquidacion_compra/retencion_$clave_acceso.pdf")->stream("retencion_$clave_acceso.pdf");
                    }
            }
        }catch (\Throwable $th) {
            echo $th;
            echo "ERROR EN EL pdf TRY CATCH";
            $err_sri = [
                "estado" => "Enviado",
                "estado1" => "Error 12",
                "mensaje" => "El pdf no se pudo enviar intente mas tarde",
                "informacion" => "Verifique si los datos del pdf de envio como del proveedor son correctos"
            ];
            return response()->json($err_sri, 500);
        }
        
        //recupera los datos de factura mediante el id de factura
        
    }
    public function guiapdf($id, $tipo, $doc)
    {
        //La guia recupera los datos mediante el id
        if ($doc == 'fv') {
            $guia = DB::select("SELECT * FROM guia_remision WHERE id_guia = $id");
            $id_cliente = $guia[0]->id_cliente;
            $id_empresa = $guia[0]->id_empresa;
            if($guia[0]->id_factura>0){
                $id_factura = $guia[0]->id_factura;
            }else{
                $id_factura = 0;
            }
            
            $id_punto_emision = $guia[0]->id_punto_emision;
            $id_establecimiento = $guia[0]->id_establecimiento;
            $clave_acceso = $guia[0]->clave_acceso;

            //recuperacion de los clientes, empresa, productos, factura y la guia del registro
            $clientes = DB::select("SELECT * FROM cliente WHERE id_cliente = $id_cliente");
            $empresas = DB::select("SELECT em.*, es.urlweb FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
            $detalles = DB::select("SELECT dgr.*, pr.cod_principal FROM detalle_guia_remision dgr INNER JOIN producto pr ON dgr.id_producto=pr.id_producto WHERE dgr.id_guia_remision = $id");
            $facturas = DB::select("SELECT * FROM factura WHERE id_factura = $id_factura");
            $guia = $guia[0];
            $cliente = $clientes[0];
            $empresa = $empresas[0];
            if($id_factura>0){
                $factura = $facturas[0];
            }else{
                $factura = [];
            }
            
            $imagen = "";
            if (file_exists(constant("DATA_EMPRESA") . "$id_empresa/imagen/" . $empresa->logo)) {
                $imagen = constant("DATA_EMPRESA") . "$id_empresa/imagen/" . $empresa->logo;
            }
            $pdf = \PDF::loadView('pdf/guia_venta', compact("guia", "cliente", "empresa", "detalles", "clave_acceso", "factura", "imagen"));
            if ($tipo == 'd') {
                return $pdf->download("$clave_acceso.pdf");
            } else {
                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/guia/$clave_acceso.pdf")->stream("$clave_acceso.pdf");
            }
        } else {
            $guia = DB::select("SELECT * FROM guia_remision WHERE id_guia = $id");
            $id_cliente = $guia[0]->id_cliente;
            $id_empresa = $guia[0]->id_empresa;
            $id_factura = $guia[0]->id_nota_venta;
            $id_punto_emision = $guia[0]->id_punto_emision;
            $id_establecimiento = $guia[0]->id_establecimiento;
            $clave_acceso = $guia[0]->clave_acceso;

            //recuperacion de los clientes, empresa, productos, factura y la guia del registro
            $clientes = DB::select("SELECT * FROM cliente WHERE id_cliente = $id_cliente");
            $empresas = DB::select("SELECT em.*, es.urlweb FROM empresa em INNER JOIN establecimiento es ON es.id_establecimiento = $id_establecimiento INNER JOIN punto_emision pe ON pe.id_punto_emision = $id_punto_emision WHERE em.id_empresa = $id_empresa");
            $detalles = DB::select("SELECT dgr.*, pr.cod_principal FROM detalle_guia_remision dgr INNER JOIN producto pr ON dgr.id_producto=pr.id_producto WHERE dgr.id_guia_remision = $id");
            $facturas = DB::select("SELECT * FROM nota_venta WHERE id_nota_venta = $id_factura");
            $guia = $guia[0];
            $cliente = $clientes[0];
            $empresa = $empresas[0];
            $factura = $facturas[0];
            $imagen = "";
            if (file_exists(constant("DATA_EMPRESA") . "$id_empresa/imagen/" . $empresa->logo)) {
                $imagen = constant("DATA_EMPRESA") . "$id_empresa/imagen/" . $empresa->logo;
            }
            $carpeta2 = constant("DATA_EMPRESA") . "$id_empresa/comprobantes/notaVenta";
            if (!file_exists($carpeta2)) {
                mkdir($carpeta2, 0755, true);
            }
            $pdf = \PDF::loadView('pdf/guia_venta', compact("guia", "cliente", "empresa", "detalles", "clave_acceso", "factura", "imagen"));
            if ($tipo == 'd') {
                return $pdf->download("$clave_acceso.pdf");
            } else {
                return $pdf->setWarnings(false)->save(constant("DATA_EMPRESA") . "$id_empresa/comprobantes/guia/$clave_acceso.pdf")->stream("$clave_acceso.pdf");
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
        $detalles = DB::select("SELECT det.*, pr.cod_principal, pr.total_ice as total_ice_pr FROM detalle_liquidacion_compra as det INNER JOIN producto pr ON det.id_producto=pr.id_producto WHERE det.id_liquidacion_compra = $id");
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
}
