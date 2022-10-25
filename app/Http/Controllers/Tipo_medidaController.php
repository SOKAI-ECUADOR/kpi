<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tipo_medida;

class Tipo_medidaController extends Controller
{
    public function todo()
    {
        $tipo = Tipo_medida::all();
        return $tipo;
    }
}
