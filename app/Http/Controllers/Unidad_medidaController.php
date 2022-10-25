<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unidad_medida;

class Unidad_medidaController extends Controller
{
    public function todo()
    {
        $tipo = Unidad_medida::all();
        return $tipo;
    }

    public function todor(Request $request)
    {
        $id = $request->id;
        $tipo = Unidad_medida::all()->where('id_tipo_medida','=',$id);
        return $tipo;
    }
}
