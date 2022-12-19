<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActaCanton;
use Carbon\Carbon;


class ActaCantonController extends Controller
{
    
    //listar actacanton por provincia
    public function buscaractacantonesxprovincia(Request $request, $provincia_id){
        
        $recupera = ActaCanton::select("*")
        ->where("acta_provincia_id", "=", $provincia_id)
        ->orderByRaw('id ASC')
        ->get();
        
        return [
            'recupera' => $recupera
        ];
    }
    //listar actacantones
    public function buscaractacantones(Request $request){
        
        $recupera = ActaCanton::select("*")
        ->where("activo", "=", 1)
        ->orderByRaw('id ASC')
        ->get();
        
        return [
            'recupera' => $recupera
        ];
    }
    
}
