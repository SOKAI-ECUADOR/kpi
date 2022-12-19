<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActaCiudad;
use Carbon\Carbon;


class ActaCiudadController extends Controller
{
    
    //listar actaciudad por provincia
    public function buscaractaciudadesxprovincia(Request $request, $provincia_id){
        
        $recupera = ActaCiudad::select("*")
        ->where("acta_provincia_id", "=", $provincia_id)
        ->orderByRaw('id ASC')
        ->get();
        
        return [
            'recupera' => $recupera
        ];
    }
    
}
