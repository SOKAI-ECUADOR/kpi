<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Util;

class AuthSRIController extends Controller
{
    protected $AuthSRI;
    public function __construct(Util $AuthSRI){
        $this->AuthSRI = $AuthSRI;
    }

    public function llamar_datos_sri($ruc){
        //return consulta_dni_sri($ruc);
        //$request = $this->AuthSRI->index($ruc);
        $request = consulta_dni_sri($ruc);
        $lugar = explode('/', $request['ubicacion_comercial']);
        if(isset($lugar[0])){
            $request["provincia"] = trim($lugar[0]);
        }
        if(isset($lugar[1])){
            $request["ciudad"] = trim($lugar[1]);
        }
        if(isset($lugar[2])){
            $request["direccion"] = trim($lugar[2]);
        }
        return $request;
    }
}
