<?php
	use GuzzleHttp\Client;
    use Illuminate\Support\Arr;

    function consulta_dni_sri($ruc){
        try {
            if(strlen($ruc) == 10) $ruc = $ruc . '001';

            $client = new Client();
            $url = "https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa";
            $res = $client->request("GET", "$url?ruc=$ruc");

            $response = $res->getBody();
            $cookieJar = $res->getHeader('Set-Cookie');
            $cookieJar = Arr::first($cookieJar);
            $cookieJar = explode(';', $cookieJar);
            $cookie = $cookieJar[0];

            $DOM = new \DOMDocument();
            $internalErrors = libxml_use_internal_errors(true);
            $DOM->loadHTML($response);
            $DOM->recover = true;
            $DOM->strictErrorChecking = false;
            libxml_use_internal_errors($internalErrors);
            $tr = $DOM->getElementsByTagName('tr');
            foreach($tr as $index => $tr){
                $res = str_replace(array("\r", "\n", "\t"), '', trim($tr->textContent)) ?? null;
                if($index >= 1 && strlen($res) > 5){
                    $datas[] = $res;
                }
            }

            $establecimientos = establecimientos($cookie);

            $names = [
                'Razón Social:' => 'razon_social',
                'RUC:' => 'ruc',
                'Nombre Comercial:' => 'nombre_comercial',
                'Estado del Contribuyente en el RUC' => 'estado_contribuyente_ruc_activo',
                'Clase de Contribuyente' => 'clase_contribuyente',
                'Tipo de Contribuyente' => 'tipo_contribuyente',
                'Obligado a llevar Contabilidad' => 'obligado_contabilidad',
                'Actividad Económica Principal' => 'actividad_economica',
                'Fecha de inicio de actividades' => 'fecha_inicio_actividades',
                'Fecha de cese de actividades' => 'fecha_cese_actividades',
                'Fecha reinicio de actividades' => 'fecha_reinicio_actividades',
                'Fecha actualización' => 'fecha_actualizacion',
                'Categoria Mi PYMES' => 'categoria_my_pymes',
            ];
            $newpush = [];
                foreach($names as $key => $name){
                    foreach ($datas as $index => $data) {
                        if(strpos($data, $key) !== false){
                            $keys = array_keys($names);
                            array_push($newpush , [$name => str_replace($keys, "", $data)]);
                        }
                    }
                }
            $newpush = Arr::collapse($newpush);
            $newpush["ubicacion_comercial"] = $establecimientos[0]['ubicacion'];
            $newpush["establecimientos"] = $establecimientos;
            return $newpush;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Este RUC no existe o no esta habilitado por el momento'], 404);
        }
    }
    function establecimientos($cookie){
        $client = new Client();
        $url = "https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-establec.jspa";
        $coockie = ['Cookie' => $cookie];
        $res = $client->request("GET", "$url", [
            'headers' => $coockie
        ]);
        $response = $res->getBody();

        $DOM = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $DOM->loadHTML($response);
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
