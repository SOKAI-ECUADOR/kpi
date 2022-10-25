<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class EjemplosController extends Controller
{

    public function ejemplos(){
        //ejemplo de envio de auth del usuario logueado dentro del back
        return $this->respondWithToken(auth()->refresh());
    }
}
