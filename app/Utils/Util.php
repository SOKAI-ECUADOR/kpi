<?php

namespace App\Utils;

class Util {
    public function index($ruc, $status=false){
        try{
            if(strlen($ruc)==10) $ruc=$ruc.'001';
            //Los datos generales del usuario SRI
            $url = "https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa";
            $datos = ["ruc" => $ruc];
            $opciones = array(
                "http" => array(
                    "header" => "Content-type: application/x-www-form-urlencoded",
                    "method" => "POST",
                    "content" => http_build_query($datos),
                ),
            );
            $contexto = stream_context_create($opciones);
            $resultado = @file_get_contents($url, false, $contexto);
        }catch(\Exception $e){
            return response()->json(['message' => 'Este RUC no existe o no esta habilitado por el momento'], 404);
        }

        try{
            $DOM = new \DOMDocument();
            $internalErrors = libxml_use_internal_errors(true);
            $DOM->loadHTML($resultado);
            $DOM->recover = true;
            $DOM->strictErrorChecking = false;
            libxml_use_internal_errors($internalErrors);
            $tr = $DOM->getElementsByTagName('tr');
            foreach($tr as $tr){
                $datas[] = trim($tr->textContent) ?? null;
            }

            //mapea cookie SRI
            $cookie = $this->cookie($url, $ruc, $status);
            //añade establecimientos del usuario del SRI
            $establecimientos = $this->establecimientos($cookie);

            $data = array(
                "razon_social" => trim(str_replace(array('Razón Social:', "\r", "\n", "\t"), '', $datas[1] ?? null)),
                "ruc" => trim(str_replace(array('RUC:', "\r", "\n", "\t"), '', $datas[3] ?? null)),
                "nombre_comercial" => trim(str_replace(array('Nombre Comercial:', "\r", "\n", "\t"), '', $datas[6] ?? null)),
                "estado_contribuyente_ruc_activo" => trim(str_replace(array('Estado del Contribuyente en el RUC', "\r", "\n", "\t"), '', $datas[8] ?? null)),
                "clase_contribuyente" => trim(str_replace(array('Clase de Contribuyente', "\r", "\n", "\t"), '', $datas[10 ?? null])),
                "tipo_contribuyente" => trim(str_replace(array('Tipo de Contribuyente', "\r", "\n", "\t"), '', $datas[12 ?? null])),
                "obligado_contabilidad" => trim(str_replace(array('Obligado a llevar Contabilidad', "\r", "\n", "\t"), '', $datas[14 ?? null])),
                "actividad_economica" => trim(str_replace(array('Actividad Económica Principal', "\r", "\n", "\t"), '', $datas[16 ?? null])),
                "fecha_inicio_actividades" => trim(str_replace(array('Fecha de inicio de actividades', "\r", "\n", "\t"), '', $datas[18 ?? null])),
                "fecha_cese_actividades" => trim(str_replace(array('Fecha de cese de actividades', ' ', "\r", "\n", "\t"), '', $datas[20 ?? null])),
                "fecha_reinicio_actividades" => trim(str_replace(array('Fecha reinicio de actividades', ' ', "\r", "\n", "\t"), '', $datas[22 ?? null])),
                "fecha_actualizacion" => trim(str_replace(array('Fecha actualización', ' ', "\r", "\n", "\t"), '', $datas[24 ?? null])),
                "categoria_my_pymes" => trim(str_replace(array('Categoria Mi PYMES', "\r", "\n", "\t"), '', $datas[26 ?? null])),
                "ubicacion_comercial" => $establecimientos[0]['ubicacion'],
                "establecimientos" => $establecimientos
            );

            return $data;
        }catch(\Exception $e){
            $data = $this->index($ruc, true);
            return $data;
        }
    }
    public function establecimientos($cookie){
        $url = "https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-establec.jspa";
        $opciones = array(
            "http" => array(
                "method" => "GET",
                "header" => "Content-type: application/x-www-form-urlencoded\r\n" .
                            "Cookie: JSESSIONID=$cookie"
            ),
        );
        $contexto = stream_context_create($opciones);
        $resultado = @file_get_contents($url, false, $contexto);
        $DOM = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $DOM->loadHTML($resultado);
        $xpath = new \DOMXpath($DOM);
        $table = $xpath->query('//table[contains(@class, "reporte")]');
        libxml_use_internal_errors($internalErrors);
        foreach($table as $index => $tbody){
            $tr = $tbody->getElementsByTagName('td');
            foreach($tr as $index => $tr){
                $datas[] = trim(str_replace(array("\r", "\n", "\t"), '', $tr->textContent ?? null));
            }
        }
        $contador = 0;
        $length = count($datas) / 4;
        for($a = 0; $a < $length; $a++){
            $establecimientos[] = ["num_establecimiento" => $datas[$contador], "nombre_comercial" => $datas[$contador+1], "ubicacion" => $datas[$contador+2], "estado" => $datas[$contador+3]];
            $contador = $contador + 4;
        }
        return $establecimientos;
    }
    public function cookie($url, $ruc, $status=false){
        $session = $_COOKIE["JSESSIONID_$ruc"] ?? null;
        if(empty($session) || $status){
            $ch = curl_init($url.'?ruc='.$ruc);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $result = curl_exec($ch);
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
            $cookies = array();
            foreach($matches[1] as $item) {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
            }
            setcookie("JSESSIONID_$ruc", $cookies['JSESSIONID'], 20);
            return $cookies['JSESSIONID'];
        }else{
            return $session;
        }
    }
}
